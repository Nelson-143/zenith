<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use app\Enums\SupplierType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use app\Scopes\AccountScope;

class Supplier extends Model
{
   
    use HasFactory;

    protected $fillable = [
        'account_id',
        'name', 'email', 'phone', 'address', 'shopname', 'type', 'photo', 'account_holder', 'account_number', 'bank_name', 'user_id', 'uuid'
    ];

    protected $casts = [
        'type' => SupplierType::class,
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, string $search = null)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }
        return $query;
    }
    public function getRouteKeyName()
    {
        return 'uuid'; // This ensures that the model is resolved using the UUID
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
