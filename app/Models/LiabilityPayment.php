<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiabilityPayment extends Model
{
   
    use HasFactory;

    // The associated table name
    protected $table = 'liability_payments';

    // Mass assignable attributes
    protected $fillable = [
        'liability_id',
        'amount_paid',
        'paid_at',
        'account_id',
    ];

    // Define the relationship to the Liability model
    public function liability()
    {
        return $this->belongsTo(Liability::class, 'liability_id');
    }

    // Define the relationship to the Account model
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}

