<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Http\Controllers\Admin\AdminCrudControllerpp\Scopes\AccountScope;

class Achievement extends Model
{
   
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points',
        'icon',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')->withTimestamps();
    }
    public function branch()
{
    return $this->belongsTo(Branch::class);
}

}
