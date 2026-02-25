<?php
namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Account extends Model
{
  
    use HasFactory;

    protected $fillable = ['name','tax_rate','currency','is_currency_active'];

    protected $casts = [
        'is_currency_active' => 'boolean',
        'tax_rate' => 'float',
    ];

    // Define the relationship with the User model
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
{
    return $this->hasMany(Product::class, 'account_id');
}
}
