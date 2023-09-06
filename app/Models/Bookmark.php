<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Bookmark extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'name',
        'data',
        'sort_order'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
    
    protected static function boot()
    {
        parent::boot();
    }
    
    public function user()
    {
        /** @var static|\Illuminate\Database\Eloquent\Model $this */
        return $this->belongsTo(User::class);
    }
}
