<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Role extends Model
{
    use UUID;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_hidden'
    ];

    /**
     * ==================== Relationships ===================================
     */

    public function user()
    {
        return $this->belongsToMany(
            User::class,
            'role_user',
            'role_id',
            'user_id'
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(
            User::class,
            'permission_role',
            'role_id',
            'permission_id'
        );
    }
}
