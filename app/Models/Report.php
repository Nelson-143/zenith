<?php
namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class Report extends Model
{
    
    use HasFactory;

    protected $fillable = ['account_id','user_id', 'type', 'data', 'file_path'];

    protected $casts = [
        'data' => 'array', // Automatically cast JSON data
    ];

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
