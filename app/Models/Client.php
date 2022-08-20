<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'google_id', 'image_path', 'phone',
    ];

    protected $appends = [
        'is_seller',
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

    public function position()
    {
        return $this->hasOne(Position::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function isSeller(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->vendor()->exists(),
        );
    }

    public function getImageUrlAttribute()
    {
        $imagePath = $this->image_path;

        if ($imagePath) {
            return Storage::disk('public')->url($imagePath);
        }

        return null;
    }

    // static callers
    public static function customers()
    {
        return Client::doesntHave('vendor');
    }

    public static function sellers()
    {
        return Client::has('vendor');
    }
}
