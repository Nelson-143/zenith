<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class Payment extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'debt_id',
        'amount_paid',
        'paid_at',
    ];

    public function debt()
{
    return $this->belongsTo(Debt::class);

}

}
