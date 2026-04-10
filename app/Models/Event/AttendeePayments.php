<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendeePayments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_attendee_id',
        'amount',
        'paid',
        'method',
        'status',
        'transaction_id',
    ];

    public function eventAttendee()
    {
        return $this->belongsTo(EventAttendee::class, 'event_attendee_id');
    }
}
