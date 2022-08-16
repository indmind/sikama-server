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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
