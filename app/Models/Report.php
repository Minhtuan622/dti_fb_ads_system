<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'start_at',
        'end_at',
        'revenue',
        'spend',
        'catse_cost',
        'expected_revenue',
        'expected_profit',
        'meta',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'meta' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}