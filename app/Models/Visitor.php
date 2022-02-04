<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'surname', 'email', 'phone', 'birthyear', 'confirmed'
    ];

    protected $attributes = [
        'confirmed' => true,
    ];

    public function getFullNameAttribute()
    {
        return "{$this->surname} {$this->name}";
    }

    public function getAgeAttribute()
    {
        if ($this->birthyear)
        {
            $current_year = Carbon::now()->year;
            return $current_year - $this->birthyear;
        } else {
            return "";
        }
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'visitor_reservations');
    }

    protected $casts = [
        'name' => 'string',
    ];

    protected $appends = ['full_name', 'age'];

}
