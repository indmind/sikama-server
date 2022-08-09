<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'image_path',
    ];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
}
