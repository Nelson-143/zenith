<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Boot the model and apply global scopes.
     */
    protected static function booted()
    {
        static::addGlobalScope('account', function (Builder $builder) {
            if (Auth::check()) {
                // Automatically filter by account_id for all authenticated users
                $builder->where('account_id', Auth::user()->account_id);
            }
        });
    }

    /**
     * Get the products associated with the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the user who created the category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Search scope for filtering categories.
     */
    public function scopeSearch(Builder $query, string $search = null)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
        }

        return $query;
    }

    /**
     * Get the branch associated with the category.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}