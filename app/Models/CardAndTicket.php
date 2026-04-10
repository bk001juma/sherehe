<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardAndTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cards_and_tickets';

    protected $fillable = [
        'name',
        'type',
        'status',
    ];
}
