<?php
namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    
    use HasFactory;

    // Specify the fillable attributes
    protected $fillable = [
        'name',
        'status',
        'account_id',
    ];

    // Define relationships to other models
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function incomeStatements()
    {
        return $this->hasMany(IncomeStatement::class);
    }

    public function balanceSheets()
    {
        return $this->hasMany(BalanceSheet::class);
    }

    public function cashFlows()
    {
        return $this->hasMany(CashFlow::class);
    }

    public function taxReports()
    {
        return $this->hasMany(TaxReport::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }
   
    protected static function booted()
    {
        static::addGlobalScope(new AccountScope);
    }
  
    // Add any additional methods or relationships as needed
}