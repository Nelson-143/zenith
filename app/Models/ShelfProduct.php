<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class ShelfProduct extends Model
{
    protected $fillable = [
        'product_id', 'unit_name', 'unit_price', 'conversion_factor', 'quantity', 'account_id',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'conversion_factor' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new AccountScope);
    }
}