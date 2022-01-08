<?php

namespace App\Models;

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

    protected $casts = [
        'name' => 'string',
    ];

    protected $appends = ['full_name'];

}
