<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class ExpenseCategory extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_id','name', 'description'];

    /**
     * Get the expenses associated with this category.
     */
    public function expense()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
      // for the branches 
      public function branch()
      {
          return $this->belongsTo(Branch::class);
      }

      //scoope
      protected static function booted()
    {
        static::addGlobalScope(new AccountScope);
    }
}

