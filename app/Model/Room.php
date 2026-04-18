<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['building_id', 'room_number', 'room_type_id', 'area', 'seat_total'];
}