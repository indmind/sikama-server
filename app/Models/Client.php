<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'google_id', 'image_path', 'phone',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($client) {
            $image = $client->image_path;

            if ($image) {
                Storage::disk('public')->delete($image);
            }
        });

        static::updating(function ($client) {
            if ($client->isDirty('image_path')) {
                $oldImagePath = $client->getOriginal('image_path');

                if ($oldImagePath) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
        });
    }

    public function location() {
        return $this->hasOne(Location::class);
    }

    public function vendor() {
        return $this->hasOne(Vendor::class, 'seller_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function getIsVendorAttribute() {
        return $this->vendor !== null;
    }
}
