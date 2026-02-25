<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Product;
use app\Scopes\AccountScope;
class StockTransfer extends Model
{
   
    use HasFactory;

    protected $fillable = [
        
        'account_id', 
        'product_id', 
        'from_location', 
        'to_location', 
        'quantity', 
        'status'
    ];
    
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
