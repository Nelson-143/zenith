<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use app\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use app\Scopes\AccountScope;
use app\Models\Customer;
/**
 * @method static where(string $string, int|string|null $id)
 * @method static findOrFail(mixed $customer_id)
 */
class Debt extends Model
{
   
    protected $fillable = [
        'customer_id', // The ID of the customer (nullable for personal debts)
        'amount',      // The total amount of the debt
        'amount_paid', // The amount paid so far (defaults to 0)
        'due_date',
        'customer_set',    // The due date for the debt
        'paid_at', 
        'account_id'    // The date when the debt was fully paid (nullable)
    ];
    // Debt.php
      // Apply the global scope
      
protected static function boot()
{
    parent::boot();
    static::creating(function ($debt) {
        $debt->uuid = (string) \Illuminate\Support\Str::uuid();
    });
}
    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}
  // for the branches 
  public function branch()
  {
      return $this->belongsTo(Branch::class);
  }
  public function payments()
{
    return $this->hasMany(Payment::class);
}
public function getRouteKeyName()
{
    return 'uuid'; // Use 'uuid' instead of 'id' for route model binding
}
public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new AccountScope); // Apply the global scope
    }
   
}