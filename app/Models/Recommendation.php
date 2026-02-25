<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
  
    use HasFactory;

    protected $fillable = ['user_id','recommendation', 'priority', 'is_read'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}
