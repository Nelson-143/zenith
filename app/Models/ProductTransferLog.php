<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTransferLog extends Model
{
    protected $fillable = ['product_id', 'from_location_id', 'to_location_id', 'quantity', 'account_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }
}