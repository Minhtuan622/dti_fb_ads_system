<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\Project;
use App\Models\FacebookAccount;
use App\Models\FacebookPage;
use App\Models\Ad;
use App\Models\Report;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function facebookAccounts()
    {
        return $this->hasMany(FacebookAccount::class);
    }

    public function facebookPages()
    {
        return $this->hasManyThrough(
            FacebookPage::class,
            FacebookAccount::class,
            'user_id',               // Foreign key on FacebookAccount
            'facebook_account_id',   // Foreign key on FacebookPage
            'id',                    // Local key on User
            'id'                     // Local key on FacebookAccount
        );
    }

    public function ads()
    {
        return $this->hasManyThrough(Ad::class, Project::class);
    }

    public function reports()
    {
        return $this->hasManyThrough(Report::class, Project::class);
    }
}
