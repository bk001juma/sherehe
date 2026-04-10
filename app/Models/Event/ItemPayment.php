<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_item_id',
        'method',
        'amount',
        'transaction_id',
    ];

    public function item()
    {
        return $this->belongsTo(\App\Models\Event\EventItem::class);
    }
}
