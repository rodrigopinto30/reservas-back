<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model {
    use HasFactory;

    protected $fillable = [
        'space_name',
        'space_desc',
        'space_capacity',
        'space_avail_from',
        'space_avail_to',
        'space_price_hour'
    ];

    public function reservation(){
        return $this->hasMany(Reservation::class, 'space_id', 'id');
    }
}
