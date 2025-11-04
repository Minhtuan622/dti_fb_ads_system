<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()
            ->projects()
            ->withCount(['facebookPages', 'ads'])
            ->latest()
            ->paginate(10);
            
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
        ]);

        $validated['user_id'] = Auth::id();
        
        Project::create($validated);
        
        return redirect()->route('projects.index')
            ->with('success', 'Dự án đã được tạo thành công.');
    }

    public function edit(Project $project)
    {
        // Kiểm tra quyền truy cập
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        // Kiểm tra quyền truy cập
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
        ]);
        
        $project->update($validated);
        
        return redirect()->route('projects.index')
            ->with('success', 'Dự án đã được cập nhật thành công.');
    }

    public function destroy(Project $project)
    {
        // Kiểm tra quyền truy cập
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }
        
        $project->delete();
        
        return redirect()->route('projects.index')
            ->with('success', 'Dự án đã được xóa thành công.');
    }
}