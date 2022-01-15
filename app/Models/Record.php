<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'action',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the User .
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
