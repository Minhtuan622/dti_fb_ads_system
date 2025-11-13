<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LarkSetting extends Model
{
    protected $fillable = [
        'webhook_url',
        'webhook_secret',
        'enabled',
        'message_template',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public static function getActiveSettings()
    {
        return self::where('enabled', true)->first();
    }
}
