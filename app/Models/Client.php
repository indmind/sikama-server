<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'google_id', 'image_path', 'phone',
    ];

    public function location() {
        return $this->hasOne(Location::class);
    }

    public function vendor() {
        return $this->hasOne(Vendor::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
