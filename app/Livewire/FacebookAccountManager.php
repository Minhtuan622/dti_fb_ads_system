<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\FacebookAccount;
use App\Models\FacebookPage;
use App\Services\FacebookAdsService;

class FacebookAccountManager extends Component
{
    use WithPagination;

    public $search = '';
    public $editingId = null;
    public $confirmingDeleteId = null;

    public $accountId;
    public $name;
    public $accessToken;
    public $status = 'active';

    protected $rules = [
        'accountId' => 'required|string|max:255',
        'name' => 'nullable|string|max:255',
        'accessToken' => 'nullable|string',
        'status' => 'required|string|in:active,inactive',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function createAccount()
    {
        $this->validate();

        FacebookAccount::create([
            'user_id' => Auth::id(),
            'account_id' => $this->accountId,
            'name' => $this->name,
            'access_token' => $this->accessToken,
            'status' => $this->status,
        ]);

        $this->reset(['accountId','name','accessToken','status','editingId']);
        $this->status = 'active';
        $this->resetPage();
    }

    public function editAccount($id)
    {
        $acc = FacebookAccount::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $acc->id;
        $this->accountId = $acc->account_id;
        $this->name = $acc->name;
        $this->accessToken = $acc->access_token;
        $this->status = $acc->status;
    }

    public function cancelEdit()
    {
        $this->reset(['editingId','accountId','name','accessToken','status']);
        $this->status = 'active';
    }

    public function updateAccount()
    {
        $this->validate();

        $acc = FacebookAccount::where('user_id', Auth::id())->findOrFail($this->editingId);
        $acc->update([
            'account_id' => $this->accountId,
            'name' => $this->name,
            'access_token' => $this->accessToken,
            'status' => $this->status,
        ]);

        $this->cancelEdit();
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = (int) $id;
    }

    public function deleteAccount()
    {
        if ($this->confirmingDeleteId) {
            FacebookAccount::where('user_id', Auth::id())
                ->where('id', $this->confirmingDeleteId)
                ->delete();

            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function refreshFromFacebook($id)
    {
        $acc = FacebookAccount::where('user_id', Auth::id())->findOrFail($id);

        // Đồng bộ thông tin tài khoản
        $fb = (new FacebookAdsService)->fetchAccount($acc->account_id);
        $acc->update([
            'name' => $fb['name'] ?? $acc->name,
            'status' => $fb['status'] ?? $acc->status,
        ]);

        // Đồng bộ các trang trực thuộc tài khoản
        $pages = (new FacebookAdsService)->fetchPages($acc->account_id);
        foreach ($pages as $p) {
            FacebookPage::updateOrCreate(
                [
                    'facebook_account_id' => $acc->id,
                    'page_id' => (string)($p['id'] ?? ''),
                ],
                [
                    'name' => $p['name'] ?? null,
                ]
            );
        }
    }

    public function render()
    {
        $accountsQuery = Auth::user()
            ->facebookAccounts()
            ->withCount(['facebookPages', 'ads'])
            ->latest();

        if ($this->search) {
            $accountsQuery->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('account_id', 'like', '%'.$this->search.'%');
            });
        }

        $facebookAccounts = $accountsQuery->paginate(10);

        return view('livewire.facebook-account-manager', compact('facebookAccounts'))
            ->layout('components.layouts.app', ['title' => 'Tài khoản Facebook']);
    }
}