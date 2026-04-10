<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventNotification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'sender_name',
        'group',
        'notification_type',
        'messages',
        'used_messages',
        'status',
    ];

    public function sms_notifications()
    {
        return $this->hasMany(\App\Models\Event\NotificationSMS::class);
    }

    public function event()
    {
        return $this->belongsTo(\App\Models\Event\Event::class);
    }
}
