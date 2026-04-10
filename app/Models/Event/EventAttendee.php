<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAttendee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'full_name',
        'phone',
        'amount',
        'paid',
        'balance',
        'status',
        'is_attending',
        'attended_at',
        'is_committee_member',
        'card_received',
        'qr_otp_code',
        'checkin_count',
        'event_attendees_category_id',
        'attending_response',
        'table_number',
    ];

    public function event()
    {
        return $this->belongsTo(\App\Models\Event\Event::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Event\AttendeePayments::class, 'event_attendee_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Event\EventAttendeesCategory::class, 'event_attendees_category_id');
    }
}
