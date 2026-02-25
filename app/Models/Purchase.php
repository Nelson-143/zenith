<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class Purchase extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'account_id',
        'supplier_id',
        'date',
        'total_amount',
        'created_by',
        'purchase_no',
        'uuid',
        'user_id',
        'updated_by',  // Add this field here
        'status', // Ensure this is included if you want to track the purchase status
    ];

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with PurchaseDetails
    public function details() // It's better to name it 'details' for clarity
    {
        return $this->hasMany(PurchaseDetails::class);
    }

    // Relationship with User for created by
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with User for updated by
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
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
