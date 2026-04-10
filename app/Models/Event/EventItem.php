<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'item_type_id',
        'name',
        'amount',
        'paid',
        'balance',
    ];

    public function item_type()
    {
        return $this->belongsTo(\App\Models\Event\ItemType::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Event\ItemPayment::class);
    }
}
