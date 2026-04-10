<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'event_id',
        'event_package_id',
        'amount',
        'paid',
        'balance',
        'status',
        'method',
        'transaction_id',
    ];

    public function event()
    {
        return $this->hasMany(\App\Models\Event\Event::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
