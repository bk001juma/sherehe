<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventDesignCard extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'event_id',
        'double_card',
        'single_card',
        'complementary_card',
        'couple_ticket',
        'single_ticket',
        'vvip_card',
        'vip_card',
        'regular_card'
    ];

    // Define the relationship to the Event model
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
