<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    
    use HasFactory;

    protected $fillable = [
        'user_id',
        'space_id',
        'reserv_name',
        'reserv_start',
        'reserv_end',
        'reserv_status',
        'reserv_create_at',
        'reserv_update_at'
    ];

    protected $hidden = [
        
    ];

    public function user(){
        return $this->belongsTo(user::class, 'user_id', 'id');
    }

    public function space(){
        return $this->belongsTo(space::class, 'space_id', 'id');
    }
}
