<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAssignedCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_assigned_card';

    protected $fillable = [
        'event_id',
        'selected_card',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
