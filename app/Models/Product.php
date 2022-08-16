<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'name', 'price', 'is_available', 'image_path',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
