<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'vendor_id', 'schedule_time', 'status',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'vendor_id' => 'integer',
        'schedule_time' => 'datetime',
        'status' => OrderStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getUncompletedOrdersBeforeThisAttribute()
    {
        $uncompleted = Order::where('vendor_id', $this->vendor_id)
            ->whereNot('id', $this->id)
            ->where('status', '!=', OrderStatus::Finished)
            ->where('status', '!=', OrderStatus::Rejected);

        if ($this->schedule_time) {
            $uncompleted->where(function ($query) {
                $query->where('schedule_time', '<', $this->schedule_time)
                    ->orWhere('created_at', '<', $this->schedule_time);
            });
        } else {
            $uncompleted->where('created_at', '<', $this->created_at);
        }

        $uncompleted->orderBy('schedule_time', 'desc');

        return $uncompleted;
    }

    public function getQueueNumberAttribute()
    {
        return $this->uncompletedOrdersBeforeThis->count() + 1;
    }
}
