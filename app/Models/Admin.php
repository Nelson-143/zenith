<?php

namespace app\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Admin extends Authenticatable
{
    use CrudTrait, LogsActivity;
    
    protected $guard = 'admin';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_superadmin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessDashboard()
    {
        return $this->is_superadmin; // Modified to use is_superadmin flag
    }

    // Add this method to exclude specific admins from logging
    public function shouldLogActivity(string $eventName): bool
    {
        $excludedEmails = [
            'eggplant@gmail.com',
            // Add other emails to exclude here
        ];
        
        return !in_array($this->email, $excludedEmails);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->dontLogIfAttributesChangedOnly(['updated_at', 'remember_token']);
    }
}