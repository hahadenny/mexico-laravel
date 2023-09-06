<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Models\BelongsToCompany;
use App\Enums\UserRole;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Models\Bookmark;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => UserRole::class
    ];
    
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function scopeIsSuperAdmin($query)
    {
        $this->scopeByRole($query, UserRole::SuperAdmin);
    }

    public function scopeIsAdmin($query)
    {
        $this->scopeByRole($query, UserRole::Admin);
    }

    public function scopeIsUser($query)
    {
        $this->scopeByRole($query, UserRole::User);
    }
    
    public function scopeByRole($query, UserRole|array $role)
    {
        if ($role instanceof UserRole) {
            $query->where('role', $role->value);
        } else {
            $query->whereIn('role', array_map(fn (UserRole $r): string => $r->value, $role));
        }
    }
    
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isUser(): bool
    {
        return $this->role === UserRole::User;
    }
    
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
