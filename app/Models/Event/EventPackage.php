<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPackage extends Model
{
    use HasFactory;
    use SoftDeletes;

//    public function user()
//    {
//        return $this->belongsTo(\App\Models\User::class);
//    }
}
