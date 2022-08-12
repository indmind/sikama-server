<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class VendorImage extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $fillable = [
        'vendor_id', 'image_path',
    ];

    public function buildSortQuery()
    {
        return static::query()->where('vendor_id', $this->vendor_id);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
