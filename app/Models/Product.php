<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use app\Enums\TaxType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use app\Scopes\AccountScope;
use App\Models\ProductLocation;
class Product extends Model
{
   
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'slug',
        'code',
        'quantity',
        'buying_price',
        'selling_price',
        'quantity_alert',
        'tax',
        'tax_type',
        'notes',
        'product_image',
        'category_id',
        'unit_id',
        'supplier_id',
        'expire_date', // Add this line
        'account_id',
    ];public function scopeSearch($query, $value)
    {
        return $query->where('name', 'like', '%'.$value.'%')
                   ->orWhere('code', 'like', '%'.$value.'%');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details(){
        return $this->hasMany(OrderDetails::class);
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }

    public function damagedProducts()
    {
        return $this->hasMany(DamagedProduct::class);
    }

      // for the branches 
    //   public function branch()
    //   {
    //       return $this->belongsTo(Branch::class);
    //   }
      
    //   public function product()
    //   {
    //       return $this->belongsTo(Product::class);
    //   }

      protected static function booted()
      {
          static::addGlobalScope(new AccountScope);
      }

      public function account()
{
    return $this->belongsTo(Account::class, 'account_id');
}



public function productLocations()
{
    return $this->hasMany(ProductLocation::class);
}

public function productTransferLogs()
{
    return $this->hasMany(ProductTransferLog::class);
}

public function getQuantityAttribute()
{
    if (auth()->check() && auth()->user()->account->is_location_setup) {
        return $this->productLocations()->sum('quantity');
    }
    return $this->attributes['quantity'] ?? 0;
}

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'product_locations')
                   ->withPivot('quantity')
                   ->withTimestamps();
    }
}

