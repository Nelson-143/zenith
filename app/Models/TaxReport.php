<?php
namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class TaxReport extends Model
{
   
    use HasFactory;

    protected $fillable = ['user_id', 'tax_collected', 'tax_owed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
  // for the branches 
  public function branch()
  {
      return $this->belongsTo(Branch::class);
  }
  

}
