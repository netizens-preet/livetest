<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Role;
use App\Status;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    // public const admin = 'admin';
    // public const customer = 'customer';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'interests',
        'status',
        'profile_photo',
        'bio',
        'notes',
        'address',
        'preferences',
        'label_color',
        'trust_score',
        'custom_css',
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
            'role' => Role::class,
            'interests' => 'array',
            'preferences' => 'array',
            'trust_score' => 'integer',
            'status' => Status::class,
            'address' => 'array',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // $user = Auth::user();
        // $panel = $panel->getId();

        // if($panel == 'admin'){
        //     dump('panel:' . $panel);
        //     dd($user->toArray());
        //     return $user->role == Role::Admin;
        // }

        // return false;
        return true;
    }

    //relation with post
    public function posts():HasMany
    {
        return $this->hasMany(Post::class);
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
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'suspended' => 'warning',
            'banned' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get the Heroicon name based on the status.
     */
    public function getStatusIcon(): string
    {
        return match ($this->status) {
            'active' => 'heroicon-m-check-circle',
            'suspended' => 'heroicon-m-exclamation-triangle',
            'banned' => 'heroicon-m-no-symbol',
            default => 'heroicon-m-question-mark-circle',
        };
    }
    // public function isAdmin(): bool
    // {
    //     return $this->hasRole(self::admin);
    // }
    // public function isCustomer(): bool
    // {
    //     return $this->hasRole(self::customer);
    // }
}
