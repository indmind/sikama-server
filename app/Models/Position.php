<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'latitude', 'longitude',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get nearest Position from latitude and longitude in kilometers
     */
    public function getDistanceFromLatLong($latitude, $longitude)
    {
        $earthRadius = 6371; // in km
        $dLat = deg2rad($latitude - $this->latitude);
        $dLon = deg2rad($longitude - $this->longitude);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earthRadius * $c;

        return $d;
    }

    /**
     * Get distance from other Position in kilometers
     */
    public function getDistanceFrom(Position $position)
    {
        return $this->getDistanceFromLatLong($position->latitude, $position->longitude);
    }
}
