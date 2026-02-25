<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'account_id', 'is_default'];

    public function productLocations()
    {
        return $this->hasMany(ProductLocation::class);
    }

    public function productTransferLogsFrom()
    {
        return $this->hasMany(ProductTransferLog::class, 'from_location_id');
    }

    public function productTransferLogsTo()
    {
        return $this->hasMany(ProductTransferLog::class, 'to_location_id');
    }
}