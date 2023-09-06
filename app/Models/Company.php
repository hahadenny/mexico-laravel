<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'description'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'api_key',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $company) {
            $company->generateAndSetApiKey();
        });
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function generateAndSetApiKey(): void
    {
        $this->api_key = static::generateApiKey();
    }
    
    public static function generateApiKey(): string
    {
        $apiKey = '';

        for ($i = 0; $i < 100; ++$i) {
            $apiKey = Str::random(64);
            $exists = static::apiKeyExists($apiKey);

            if (! $exists) {
                break;
            }
        }

        if ($exists) {
            throw new \Exception('Failed to generate a unique key.');
        }

        return $apiKey;
    }

    public static function apiKeyExists(string $apiKey): bool
    {
        return static::query()->withTrashed()->where('api_key', $apiKey)->exists();
    }
}
