<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function otherVisitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function contactPerson()
    {
        return $this->hasOne(Visitor::class);
    }

}
