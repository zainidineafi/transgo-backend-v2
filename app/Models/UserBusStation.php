<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBusStation extends Model
{
    protected $table = 'user_bus_station';

    protected $fillable = [
        'user_id',
        'bus_station_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function busStation()
    {
        return $this->belongsTo(BusStation::class, 'bus_station_id');
    }
}
