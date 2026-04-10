<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempOTP extends Model
{
    protected $fillable = [
        'otp',
        'phone',
        'otp_expires_at',
        'otp_session',
    ];

    use HasFactory;
    use SoftDeletes;
}
