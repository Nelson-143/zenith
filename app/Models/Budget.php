<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class Budget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 
        'user_id',
        'category_id', 
        'amount',
        'start_date',
        'end_date',
        'branch_id',
      
    ];

    /**
     * Get the user associated with the budget.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the expenses associated with this budget.
     */
    public function expenses() // Corrected relationship name
    {
        return $this->hasMany(Expense::class, 'budget_id');
    }

    /**
     * Get the category associated with this budget.
     */
    public function category()
    {
        return $this->belongsTo(BudgetCategory::class, 'category_id');
    }

    /**
     * Calculate the remaining budget.
     *
     * @return float
     */
    public function remainingBudget()
    {
        $totalExpenses = $this->expenses()->sum('amount'); // Use the corrected relationship name
        return $this->amount - $totalExpenses;
    }

    protected static function booted()
    {
        static::addGlobalScope(new AccountScope);
    }
}
