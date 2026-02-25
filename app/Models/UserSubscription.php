<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Scopes\AccountScope;
class UserSubscription extends Model
{
    use CrudTrait;
    
    use HasFactory;

    protected $fillable = ['user_id', 'subscription_id', 'starts_at', 'ends_at'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class, 'user_subscription_id', 'id');
    }

   
}
