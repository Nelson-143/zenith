<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class Expense extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'expense';
    protected $fillable = [
        'account_id',
        'user_id',
        'category_id',
        'amount',
        'description',
        'expense_date',
        'attachment',
    ];

    /**
     * Get the category associated with the expense.
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
       
    }
    //scope to filter expenses by account
    protected static function booted()
    {
        static::addGlobalScope(new AccountScope);
    }
    /**
     * Get the user associated with the expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

      // for the branches 
      public function branch()
      {
          return $this->belongsTo(Branch::class);
      }
      public function budget()
      {
        return $this->hasMany(Expense::class, 'budget_id');


      }
    
  }



