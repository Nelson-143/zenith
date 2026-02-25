<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class PurchaseDetails extends Model
{
   
    use HasFactory;

    protected $fillable = [
        'account_id',
        'purchase_id',
        'product_id',
        'quantity',
        'unitcost',
        'total',
        'previous_stock',
        'current_stock',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

      // for the branches 
      public function branch()
      {
          return $this->belongsTo(Branch::class);
      }
      protected static function booted()
      {
          static::addGlobalScope(new AccountScope);
      }

}
