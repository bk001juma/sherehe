<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'message',
        'category',
        'subject',
        'rating',
        'status',
        'admin_reply',
    ];

    /**
     * Default values for attributes
     */
    protected $attributes = [
        'category' => 'general',
        'subject' => 'App Feedback',
        'status' => 'pending',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
