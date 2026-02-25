<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class DamagedProduct extends Model
{
  
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'location', 
        'quantity', 
        'reason'
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
      
  
}

