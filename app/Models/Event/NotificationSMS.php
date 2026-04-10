<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationSMS extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_notification_id',
        'event_attendee_id',
        'phone',
        'sms',
        'characters',
        'used_messages',
        'status',
    ];

    public function event_notification()
    {
        return $this->belongsTo(\App\Models\Event\EventNotification::class);
    }

    public function event_attendee()
    {
        return $this->belongsTo(\App\Models\Event\EventAttendee::class);
    }
}
