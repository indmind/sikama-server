<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'category_id', 'name', 'description', 'is_verified', 'is_active', 'verified_by',
    ];

    public function seller()
    {
        return $this->belongsTo(Client::class, 'seller_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(VendorImage::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function verify()
    {
        $this->is_verified = true;
        $this->verified_by = auth()->id();
        $this->save();
    }

    public function unverify()
    {
        $this->is_verified = false;
        $this->verified_by = null;
        $this->save();
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        return $this->seller->position?->getDistanceFromLatLong($latitude, $longitude) ?? -1;
    }

    /**
     * Get nearest vendors from provided latitude and longitude
     *
     * returns list of
     * [
     *  'vendor' => Vendor,
     *  'distance' => distance in meters
     * ]
     */
    public static function getNearestVendors($latitude, $longitude)
    {
        $vendors = self::where('is_active', true)->get();
        $distanceTreshold = config('distance_treshold', 3); // in kilometers

        $nearests = collect();

        foreach ($vendors as $vendor) {
            $distance = $vendor->getDistanceFrom($latitude, $longitude);

            if ($distance != -1 && $distance <= $distanceTreshold) {
                // add vendor to nearest vendors with distance
                $vendor->distance = $distance;
                $nearests->push($vendor);
            }
        }

        return $nearests->sortBy('distance');
    }
}
