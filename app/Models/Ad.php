<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'facebook_account_id',
        'facebook_page_id',
        'ad_id',
        'post_id',
        'status',
        'spend',
        'impressions',
        'clicks',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function facebookAccount()
    {
        return $this->belongsTo(FacebookAccount::class);
    }

    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class);
    }
}