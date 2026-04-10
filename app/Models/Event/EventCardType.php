<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventCardType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'single_amount',
        'double_amount',
        'regular_amount',
        'vip_amount',
        'vvip_amount',
    ];

    public function event()
    {
        return $this->belongsTo(\App\Models\Event\Event::class);
    }
}
