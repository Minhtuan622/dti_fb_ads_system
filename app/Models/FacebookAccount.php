<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'account_id', 'name', 'access_token', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facebookPages()
    {
        return $this->hasMany(FacebookPage::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}