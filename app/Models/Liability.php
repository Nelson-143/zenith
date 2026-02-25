<?php
namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Liability extends Model
{
    
    protected $fillable = [
        'name',
        'amount',
        'interest_rate',
        'due_date',
        'priority',
        'type',
        'remaining_balance',
        'account_id'
    ];

    protected $dates = ['due_date'];

    // Automatically calculate priority based on due date and interest
    protected function priority(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->due_date < now()->addDays(30)) return 'high';
                if ($this->interest_rate > 15) return 'high';
                if ($this->interest_rate > 5) return 'medium';
                return $value;
            }
        );
    }

    // Calculate days until due
    public function getDaysUntilDueAttribute()
    {
        return $this->due_date ? now()->diffInDays($this->due_date, false) : null;
    }

    // Check if debt is overdue
    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < now();
    }
}