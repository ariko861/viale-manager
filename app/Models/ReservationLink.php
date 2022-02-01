<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReservationLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'link_token', 'registered_at',
    ];

    public function generateLinkToken() {
        $this->link_token = Str::uuid();
    }

    public function getLink() {
        return urldecode(route('confirmation') . '?link_token=' . $this->link_token);
    }

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }
}
