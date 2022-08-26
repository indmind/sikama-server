<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'name', 'price', 'is_available', 'image_path',
    ];

    protected $appends = [
        'image_url',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
