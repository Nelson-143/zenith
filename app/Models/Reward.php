<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class Reward extends Model
{
   
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points_required',
    ];

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }
      // for the branches 
      public function branch()
      {
          return $this->belongsTo(Branch::class);
      }
      
}
