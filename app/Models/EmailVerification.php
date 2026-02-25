<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
   
    protected $fillable = [
        'user_id',
        'email',
        'token',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}