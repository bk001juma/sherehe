<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGallery extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'type', 'url'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
