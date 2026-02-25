<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class BudgetCategory extends Model
{
   
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['account_id','name', 'description'];
    
    public function branch()
      {
          return $this->belongsTo(Branch::class);
      }
      public function expense()
    {
        return $this->hasMany(Budget::class, 'category_id');
    }

    
}
