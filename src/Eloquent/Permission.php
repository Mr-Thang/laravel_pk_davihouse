<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Permission extends Model
{
    use UUID;

    protected $fillable = [
        'group',
        'name',
        'display_name',
        'controller',
        'action',
    ];

    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'permission_role',
            'permission_id',
            'role_id'
        );
    }
}
