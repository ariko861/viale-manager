<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VisitorReservation extends Pivot
{

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    protected $table = 'visitor_reservation';

    public $incrementing = true;


}
