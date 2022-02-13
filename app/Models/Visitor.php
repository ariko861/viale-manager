<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'surname', 'email', 'phone', 'birthyear', 'confirmed'
    ];

    protected $attributes = [
        'confirmed' => true,
    ];

    public function getFullNameAttribute()
    {
        $surname = $this->surname === "x-inconnu" ? 'Prénom inconnu' : $this->surname;
        $name = $this->name === "x-inconnu" ? 'Nom inconnu' : $this->name;

        return "{$surname} {$name}";
    }

    public function getAgeAttribute()
    {
        if ($this->birthyear)
        {
            $current_year = Carbon::now()->year;
            return $current_year - $this->birthyear;
        } else {
            return "Âge inconnu";
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
