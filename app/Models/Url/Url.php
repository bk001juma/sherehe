<?php

namespace App\Models\Url;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'urls';

    protected $fillable = [
        'original_url',
        'short_code',
    ];
}
