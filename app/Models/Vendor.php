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

    public function seller() {
        return $this->belongsTo(Client::class, 'seller_id');
    }

    public function verifiedBy() {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function images() {
        return $this->hasMany(VendorImage::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function verify() {
        $this->is_verified = true;
        $this->verified_by = auth()->id();
        $this->save();
    }

    public function unverify() {
        $this->is_verified = false;
        $this->verified_by = null;
        $this->save();
    }
}
