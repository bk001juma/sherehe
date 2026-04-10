<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAttendeesCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_attendees_categories';

    protected $fillable = [
        'name',
        'event_id',
    ];

    public function eventAttendees()
    {
        return $this->hasMany(EventAttendee::class, 'event_attendees_category_id');
    }
}
