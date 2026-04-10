<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'phone_number',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
