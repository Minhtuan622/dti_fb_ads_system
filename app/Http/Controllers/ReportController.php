<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Report;
use App\Services\LarkNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $projects = $user->projects;
        
        $query = Report::query()
            ->whereIn('project_id', $projects->pluck('id'));
            
        // Filter by project if provided
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        
        // Search by name if provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $reports = $query->latest()->paginate(10);
        
        return view('reports.index', compact('reports', 'projects'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        $projects = Auth::user()->projects;
        return view('reports.create', compact('projects'));
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'revenue' => 'nullable|numeric|min:0',
            'spend' => 'nullable|numeric|min:0',
            'catse_cost' => 'nullable|numeric|min:0',
            'expected_revenue' => 'nullable|numeric|min:0',
            'expected_profit' => 'nullable|numeric',
        ]);
        
        // Verify user owns the project
        $project = Project::findOrFail($request->project_id);
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        Report::create($validated);
        
        return redirect()->route('reports.index')
            ->with('success', 'Báo cáo đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(Report $report)
    {
        // Authorize that user owns the project of this report
        $this->authorize('update', $report);
        
        $projects = Auth::user()->projects;
        return view('reports.edit', compact('report', 'projects'));
    }

    /**
     * Update the specified report in storage.
     */
    public function update(Request $request, Report $report)
    {
        // Authorize that user owns the project of this report
        $this->authorize('update', $report);
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'revenue' => 'nullable|numeric|min:0',
            'spend' => 'nullable|numeric|min:0',
            'catse_cost' => 'nullable|numeric|min:0',
            'expected_revenue' => 'nullable|numeric|min:0',
            'expected_profit' => 'nullable|numeric',
        ]);
        
        // Verify user owns the project
        $project = Project::findOrFail($request->project_id);
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $report->update($validated);
        
        return redirect()->route('reports.index')
            ->with('success', 'Báo cáo đã được cập nhật thành công.');
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy(Report $report)
    {
        // Authorize that user owns the project of this report
        $this->authorize('delete', $report);
        
        $report->delete();
        
        return redirect()->route('reports.index')
            ->with('success', 'Báo cáo đã được xóa thành công.');
    }

    /**
     * Gửi báo cáo lên Lark
     */
    public function sendToLark(Report $report)
    {
        // Authorize that user owns the project of this report
        $this->authorize('update', $report);
        
        $larkService = new LarkNotificationService();
        $success = $larkService->sendReportFromModel($report);
        
        if ($success) {
            return redirect()->route('reports.index')
                ->with('success', 'Báo cáo đã được gửi lên Lark thành công!');
        } else {
            return redirect()->route('reports.index')
                ->with('error', 'Không thể gửi báo cáo lên Lark. Vui lòng kiểm tra cấu hình webhook.');
        }
    }
}