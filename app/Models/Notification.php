<?php

namespace app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Notifications\DatabaseNotification as NotificationBase;
use app\Scopes\AccountScope;
class Notification extends NotificationBase
{
  
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $incrementing = false; // Since the ID is a UUID
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'type',
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

}