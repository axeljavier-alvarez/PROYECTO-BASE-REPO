<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;
    use Searchable, SoftDeletes;

    public const DEFAULTPASS = 'password';
    public const USERS_TYPES = [
        'Interno',
        'Externo'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cui',
        'email',
        'password',
        'area_id',
        'user_type',
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
    public function initials(): string{
        return Str::of($this->information?->nombre_corto)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function area() : BelongsTo {
        return $this->belongsTo(Area::class);
    }

    public function information() : HasOne {
        return $this->hasOne(UserInformation::class);
    }

    public function getRoleNameAttribute() {
        return $this->roles->pluck('name')->first();
    }

    public function getMenuAttribute() {
        $allowedPermissions = $this->getAllPermissions()
            ->where('module','menu')
            ->pluck('name')
            ->toArray();

        if (empty($allowedPermissions)) {
            return [];
        }

        $pages = Page::with(['parent', 'children'])
            ->where('state',true)
            ->orderBy('order')
            ->get();

        $allowedPages = $pages->filter(function ($page) use ($allowedPermissions) {
            return in_array($page->permission_name, $allowedPermissions);
        });

        $menu = collect();

         foreach ($allowedPages as $page) {

            if ($page->parent) {
                $menu->push($page->parent);
            }

            if (!$page->parent) {
                $menu->push($page);
            }
        }

        $menu = $menu->unique('id')->sortBy('order')->values();

        $menu->each(function ($parent) use ($allowedPages) {

            $parent->childrens = $allowedPages
                ->where('page_id', $parent->id)
                ->sortBy('order')
                ->values();
        });

        return $menu->values()->all();
    }

    public function getNombreCortoAttribute() {
        return $this->information?->nombre_corto ?? null;
    }

    public function getUrlPhotoAttribute() {
        return $this->information?->url_photo ?? null;
    }
}
