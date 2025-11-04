<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Report;
use App\Services\FacebookAdsService;
use App\Services\PosService;
use App\Services\ReportService;

class ReportGenerator extends Component
{
    public $projectId;
    public $adsId;
    public $pageId;
    public $postId;
    public $endAt;

    public $deliveryRate = 0.94;      // Tỷ lệ giao (0..1)
    public $catseCostPerHour = 0;     // Chi phí catse/giờ

    public $isRunning = false;
    public $startedAt;
    public $lastComputedAt;
    public $currentReport = [];

    protected $rules = [
        'projectId' => 'required|exists:projects,id',
        'adsId' => 'required|string',
        'pageId' => 'nullable|string',
        'postId' => 'nullable|string',
        'endAt' => 'required|date',
        'deliveryRate' => 'required|numeric|min:0|max:1',
        'catseCostPerHour' => 'required|numeric|min:0',
    ];

    public function start(): void
    {
        $this->validate();

        $this->isRunning = true;
        $this->startedAt = now();
        $this->generateOnce();
    }

    public function stop(): void
    {
        $this->finalize();
        $this->isRunning = false;
    }

    public function poll(): void
    {
        if (! $this->isRunning) {
            return;
        }

        if (now()->gte(Carbon::parse($this->endAt))) {
            $this->finalize();
            $this->isRunning = false;
            return;
        }

        $this->generateOnce();
    }

    protected function generateOnce(): void
    {
        $fb = (new FacebookAdsService)->fetchRealtime($this->adsId, $this->pageId, $this->postId);
        $pos = (new PosService)->fetchRealtime((int) $this->projectId, $this->endAt);

        $elapsedMinutes = $this->startedAt ? now()->diffInMinutes($this->startedAt) : 0;
        $catseCost = ($elapsedMinutes / 60) * (float) $this->catseCostPerHour;

        $options = [
            'catse_cost' => $catseCost,
            'delivery_rate' => (float) $this->deliveryRate,
        ];

        $this->currentReport = (new ReportService)->aggregateAndCompute($fb, $pos, $options);
        $this->lastComputedAt = now();
    }

    protected function finalize(): void
    {
        if (! $this->currentReport || ! $this->projectId) {
            return;
        }

        Report::create([
            'project_id' => $this->projectId,
            'name' => 'Realtime ' . now()->format('Y-m-d H:i'),
            'start_at' => $this->startedAt,
            'end_at' => now(),
            'revenue' => (float) ($this->currentReport['revenue'] ?? 0),
            'spend' => (float) ($this->currentReport['spend'] ?? 0),
            'catse_cost' => (float) ($this->currentReport['catse_cost'] ?? 0),
            'expected_revenue' => (float) ($this->currentReport['expected_revenue'] ?? 0),
            'expected_profit' => (float) ($this->currentReport['expected_profit'] ?? 0),
            'meta' => $this->currentReport['meta'] ?? [],
        ]);
    }

    public function render()
    {
        $projects = Auth::user()->projects()->latest()->get();

        $reports = Report::whereIn('project_id', $projects->pluck('id'))
            ->latest()
            ->paginate(10);

        return view('livewire.report-generator', compact('projects', 'reports'))
            ->layout('components.layouts.app', ['title' => 'Báo cáo']);
    }
}