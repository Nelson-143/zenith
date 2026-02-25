<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;

class Mission extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points',
        'due_date',
        'status',
    ];

    public function userMissions()
    {
        return $this->hasMany(UserMission::class);
    }
      // for the branches 
      public function branch()
      {
          return $this->belongsTo(Branch::class);
      }
   
}
