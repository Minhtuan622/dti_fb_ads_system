<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad;
use App\Models\Project;
use App\Services\FacebookAdsService;

class AdManager extends Component
{
    use WithPagination;

    public $filterProjectId = null;
    public $search = '';

    public $editingId = null;

    public $projectId;
    public $facebookAccountId;
    public $facebookPageId;
    public $adId;
    public $postId;
    public $status = 'active';
    public $spend = 0;
    public $impressions = 0;
    public $clicks = 0;

    protected $rules = [
        'projectId' => 'required|exists:projects,id',
        'adId' => 'required|string|max:255',
        'postId' => 'nullable|string|max:255',
        'facebookAccountId' => 'nullable|exists:facebook_accounts,id',
        'facebookPageId' => 'nullable|exists:facebook_pages,id',
        'status' => 'required|string|in:active,inactive',
        'spend' => 'nullable|numeric|min:0',
        'impressions' => 'nullable|integer|min:0',
        'clicks' => 'nullable|integer|min:0',
    ];

    public function updatedFilterProjectId()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function createAd()
    {
        $this->validate();

        Ad::create([
            'project_id' => $this->projectId,
            'facebook_account_id' => $this->facebookAccountId,
            'facebook_page_id' => $this->facebookPageId,
            'ad_id' => $this->adId,
            'post_id' => $this->postId,
            'status' => $this->status,
            'spend' => $this->spend ?? 0,
            'impressions' => $this->impressions ?? 0,
            'clicks' => $this->clicks ?? 0,
        ]);

        $this->reset(['projectId','facebookAccountId','facebookPageId','adId','postId','status','spend','impressions','clicks','editingId']);
        $this->status = 'active';
        $this->resetPage();
    }

    public function editAd($id)
    {
        $ad = Ad::whereHas('project', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
        $this->editingId = $ad->id;
        $this->projectId = $ad->project_id;
        $this->facebookAccountId = $ad->facebook_account_id;
        $this->facebookPageId = $ad->facebook_page_id;
        $this->adId = $ad->ad_id;
        $this->postId = $ad->post_id;
        $this->status = $ad->status;
        $this->spend = $ad->spend;
        $this->impressions = $ad->impressions;
        $this->clicks = $ad->clicks;
    }

    public function cancelEdit()
    {
        $this->reset(['editingId','projectId','facebookAccountId','facebookPageId','adId','postId','status','spend','impressions','clicks']);
        $this->status = 'active';
    }

    public function updateAd()
    {
        $this->validate();

        $ad = Ad::whereHas('project', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($this->editingId);
        $ad->update([
            'project_id' => $this->projectId,
            'facebook_account_id' => $this->facebookAccountId,
            'facebook_page_id' => $this->facebookPageId,
            'ad_id' => $this->adId,
            'post_id' => $this->postId,
            'status' => $this->status,
            'spend' => $this->spend ?? 0,
            'impressions' => $this->impressions ?? 0,
            'clicks' => $this->clicks ?? 0,
        ]);

        $this->cancelEdit();
        $this->resetPage();
    }

    public function deleteAd($id)
    {
        Ad::whereHas('project', fn($q) => $q->where('user_id', Auth::id()))
            ->where('id', $id)
            ->delete();

        $this->resetPage();
    }

    public function refreshFromFacebook($id)
    {
        $ad = Ad::whereHas('project', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
        $fb = (new FacebookAdsService)->fetchRealtime($ad->ad_id, $ad->facebook_page_id, $ad->post_id);

        $ad->update([
            'spend' => (float)($fb['spend'] ?? $ad->spend),
            'impressions' => (int)($fb['impressions'] ?? $ad->impressions),
            'clicks' => (int)($fb['clicks'] ?? $ad->clicks),
        ]);
    }

    public function render()
    {
        $projectIds = Auth::user()->projects()->pluck('id');

        $adsQuery = Ad::whereIn('project_id', $projectIds)->latest();

        if ($this->filterProjectId) {
            $adsQuery->where('project_id', $this->filterProjectId);
        }

        if ($this->search) {
            $adsQuery->where(function ($q) {
                $q->where('ad_id', 'like', '%'.$this->search.'%')
                  ->orWhere('post_id', 'like', '%'.$this->search.'%');
            });
        }

        $ads = $adsQuery->paginate(10);
        $projects = Project::where('user_id', Auth::id())->latest()->get();

        return view('livewire.ad-manager', compact('ads', 'projects'))
            ->layout('components.layouts.app', ['title' => 'Quảng cáo']);
    }
}