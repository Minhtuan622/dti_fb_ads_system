<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $projectIds = Auth::user()->projects()->pluck('id');
        
        $adsQuery = Ad::whereIn('project_id', $projectIds)
            ->with('project')
            ->latest();

        // Filter by project
        if ($request->filled('project_id')) {
            $adsQuery->where('project_id', $request->project_id);
        }

        // Search by ad_id or post_id
        if ($request->filled('search')) {
            $search = $request->search;
            $adsQuery->where(function ($q) use ($search) {
                $q->where('ad_id', 'like', '%' . $search . '%')
                  ->orWhere('post_id', 'like', '%' . $search . '%');
            });
        }

        $ads = $adsQuery->paginate(10)->withQueryString();
        $projects = Project::where('user_id', Auth::id())->latest()->get();

        return view('ads.index', compact('ads', 'projects'));
    }

    public function create()
    {
        $projects = Project::where('user_id', Auth::id())->latest()->get();
        
        return view('ads.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'ad_id' => 'required|string|max:255|unique:ads,ad_id',
            'post_id' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'spend' => 'nullable|numeric|min:0',
            'impressions' => 'nullable|integer|min:0',
            'clicks' => 'nullable|integer|min:0',
        ]);

        // Verify project belongs to user
        $project = Project::where('id', $validated['project_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Ad::create([
            'project_id' => $validated['project_id'],
            'ad_id' => $validated['ad_id'],
            'post_id' => $validated['post_id'],
            'status' => $validated['status'],
            'spend' => $validated['spend'] ?? 0,
            'impressions' => $validated['impressions'] ?? 0,
            'clicks' => $validated['clicks'] ?? 0,
        ]);

        return redirect()->route('ads.index')
            ->with('success', 'Quảng cáo đã được tạo thành công!');
    }

    public function edit(Ad $ad)
    {
        // Verify ad belongs to user through project
        if (!$ad->project || $ad->project->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa quảng cáo này.');
        }

        $projects = Project::where('user_id', Auth::id())->latest()->get();
        
        return view('ads.edit', compact('ad', 'projects'));
    }

    public function update(Request $request, Ad $ad)
    {
        // Verify ad belongs to user through project
        if (!$ad->project || $ad->project->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật quảng cáo này.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'ad_id' => 'required|string|max:255|unique:ads,ad_id,' . $ad->id,
            'post_id' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'spend' => 'nullable|numeric|min:0',
            'impressions' => 'nullable|integer|min:0',
            'clicks' => 'nullable|integer|min:0',
        ]);

        // Verify project belongs to user
        $project = Project::where('id', $validated['project_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $ad->update([
            'project_id' => $validated['project_id'],
            'ad_id' => $validated['ad_id'],
            'post_id' => $validated['post_id'],
            'status' => $validated['status'],
            'spend' => $validated['spend'] ?? 0,
            'impressions' => $validated['impressions'] ?? 0,
            'clicks' => $validated['clicks'] ?? 0,
        ]);

        return redirect()->route('ads.index')
            ->with('success', 'Quảng cáo đã được cập nhật thành công!');
    }

    public function destroy(Ad $ad)
    {
        // Verify ad belongs to user through project
        if (!$ad->project || $ad->project->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa quảng cáo này.');
        }

        $ad->delete();

        return redirect()->route('ads.index')
            ->with('success', 'Quảng cáo đã được xóa thành công!');
    }
}