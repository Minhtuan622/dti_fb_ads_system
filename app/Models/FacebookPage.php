<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    use HasFactory;

    protected $fillable = ['facebook_account_id', 'page_id', 'name'];

    public function facebookAccount()
    {
        return $this->belongsTo(FacebookAccount::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}