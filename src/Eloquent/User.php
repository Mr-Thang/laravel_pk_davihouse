<?php

namespace CMS\Package\Eloquent;

use CMS\Package\Traits\UUID;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, UUID;

    protected $fillable = [
        'id',
        'user_name',
        'email',
        'password',
        'email_verified_at',
        'phone_number',
        'status',
        'provider',
        'latest_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPassWordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'creator_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class)->withDefault();
    }

    public function posts()
    {
        return $this->hasOne(Post::class, 'creator_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'creator_id');
    }

    public function banner()
    {
        return $this->hasMany(Banner::class);
    }

    public function expert()
    {
        return $this->hasOne(Expert::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id'
        )->orderBy('name', 'ASC');
    }
}
