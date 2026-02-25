<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
//log
class ShelfStockLog extends Model
{
    protected $fillable = [
        'shelf_product_id', 'quantity_change', 'action', 'user_id', 'account_id', 'notes',
    ];

    protected $casts = [
        'quantity_change' => 'decimal:2',
    ];

    public function shelfProduct()
    {
        return $this->belongsTo(ShelfProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new AccountScope);
    }
}