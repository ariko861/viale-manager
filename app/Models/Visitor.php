<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'surname', 'email', 'phone', 'birthyear',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->surname} {$this->name}";
    }

    public function getAgeAttribute()
    {
        $current_year = Carbon::now()->year;
        return $current_year - $this->birthyear;
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'visitor_reservation');
    }

    protected $casts = [
        'name' => 'string',
    ];

    protected $appends = ['full_name'];

}
