<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class ProjectManager extends Component
{
    public $name;
    public $description;
    public $status = 'active';
    public $editingId = null;
    public $confirmingDeleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|string|in:active,inactive',
    ];

    public function createProject()
    {
        $this->validate();

        Project::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->reset(['name', 'description', 'status', 'editingId']);
        $this->status = 'active';
    }

    public function editProject($id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->status = $project->status;
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'name', 'description', 'status']);
        $this->status = 'active';
    }

    public function updateProject()
    {
        $this->validate();

        $project = Project::where('user_id', Auth::id())->findOrFail($this->editingId);
        $project->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->cancelEdit();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = (int) $id;
    }

    public function deleteProject()
    {
        if ($this->confirmingDeleteId) {
            Project::where('user_id', Auth::id())
                ->where('id', $this->confirmingDeleteId)
                ->delete();

            $this->confirmingDeleteId = null;
        }
    }

    public function render()
    {
        $projects = Auth::user()
            ->projects()
            ->withCount(['facebookPages', 'ads'])
            ->latest()
            ->paginate(10);

        return view('livewire.project-manager', compact('projects'))
            ->layout('components.layouts.app', ['title' => 'Dự án']);
    }
}