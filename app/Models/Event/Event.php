<?php

namespace App\Models\Event;

use App\Models\CardAndTicket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'event_package_id',
        'event_category_id',
        'event_name',
        'family_name',
        'description',
        'language',
        'image',
        'location',
        'venue',
        'media_type',
        'video_link',
        'sms_balance',
        'card_balance',
        'whatsapp_balance',
        'contact_phone_1',
        'contact_phone_2',
        'contribution_deadline',
        'payment_numbers',
        'status',
        'event_date',
        'event_end_date',
        'mr_name',
        'mrs_name',
        'welcome_note',
        'maps_location',
        'dress_code',
        'event_time',
        'church_name',
        'church_time',
        'card_and_ticket_id',
        'initial_payment',
        'final_payment',
        'top',
        'left',
        'font_size',
        'color',
        'qr_top',
        'qr_left',
        'qr_width',
        'qr_height',
        'qr_code_font_size',
        'card_type_font_size'

    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Event\EventCategory::class, 'event_category_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Event\EventPackage::class, 'event_package_id');
    }

    public function package_payment()
    {
        return $this->hasOne(\App\Models\Event\EventPayment::class);
    }

    public function items()
    {
        return $this->hasMany(\App\Models\Event\EventItem::class);
    }

    public function pledges()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class);
    }

    public function prtial_paid_pledges()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class)->where('paid', '>', 0)->where('paid', '<>', \DB::raw('amount'));
    }

    public function incomplete_paid_pledges()
    {
        // return $this->hasMany(\App\Models\Event\EventAttendee::class)->where('paid', '>=', 0)->where('paid', '<>', \DB::raw('amount'));
        return $this->hasMany(\App\Models\Event\EventAttendee::class)
            ->where(function ($query) {
                $query->where('paid', '=', 0)              // Paid is 0
                    ->orWhereColumn('paid', '<>', 'amount'); // Paid is not equal to the total amount
            });
    }



    public function complete_paid_pledges()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class)->whereRaw('event_attendees.paid >= event_attendees.amount');
    }

    public function not_paid_pledges()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class)->where('paid', 0);
    }

    public function card_types()
    {
        return $this->hasOne(\App\Models\Event\EventCardType::class);
    }

    public function single_invitations()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class)->where('paid', '>=', $this->card_types->single_amount)->where('paid', '<', $this->card_types->double_amount);
    }

    public function double_invitations()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class)->where('paid', '>=', $this->card_types->double_amount);
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Event\EventNotification::class);
    }

    public function designCard()
    {
        return $this->hasOne(EventDesignCard::class);
    }

    public function attendeesCategories()
    {
        return $this->hasMany(EventAttendeesCategory::class, 'event_id');
    }

    public function isCompleted()
    {
        return $this->event_date && $this->event_date < Carbon::today();
    }

    public function isActive()
    {
        return $this->event_date === null ||  $this->event_date >= Carbon::today();
    }

    public function countPledgesWithCard()
    {
        return $this->pledges()->where('card_received', true)->count();
    }

    public function countPledgesAttending()
    {
        return $this->pledges()->where('is_attending', true)->count();
    }

    public function contDoubleInvitationWithCard()
    {
        return $this->pledges()->where('paid', '>=', $this->card_types->double_amount)->where('card_received', true)->count();
    }

    public function contSingleInvitationWithCard()
    {
        return $this->pledges()->where('paid', '>=', $this->card_types->single_amount)->where('paid', '<', $this->card_types->double_amount)->where('card_received', true)->count();
    }

    public function rsvps()
    {
        return $this->hasMany(\App\Models\Event\EventRsvp::class);
    }

    public function getTotalUsedMessagesAttribute()
    {
        return $this->notifications->sum(function ($notification) {
            return $notification->sms_notifications->sum('used_messages');
        });
    }

    public function cardAndTicket()
    {
        return $this->belongsTo(CardAndTicket::class, 'card_and_ticket_id');
    }

    public function attendees()
    {
        return $this->hasMany(\App\Models\Event\EventAttendee::class, 'event_id');
    }
}
