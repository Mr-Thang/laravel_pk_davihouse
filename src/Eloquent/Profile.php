<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Profile extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'avatar',
        'email',
        'phone_number',
        'occupation',
        'company_name',
        'website',
        'birthday',
        'birthplace',
        'address',
        'latitude',
        'longitude',
        'province_id',
        'district_id',
        'ward_id',
        'post_code',
        'linked_in',
        'facebook',
        'twitter',
        'instagram',
        'religious_beliefs',
        'gender',
        'relation',
        'about',
        'creator_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
