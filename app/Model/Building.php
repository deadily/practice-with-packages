<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'address'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'building_id');
    }

    public function recalculateStats(): void
    {
        $totalArea = $this->rooms()->sum('area');
        $totalSeats = $this->rooms()->sum('seat_total');

        $this->setAttribute('calculated_area', $totalArea);
        $this->setAttribute('calculated_seats', $totalSeats);

    }

    public function getCalculatedAreaAttribute(): float
    {
        return $this->rooms()->sum('area');
    }

    public function getCalculatedSeatsAttribute(): int
    {
        return $this->rooms()->sum('seat_total');
    }
}