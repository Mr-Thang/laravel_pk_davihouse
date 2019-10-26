<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use CMS\Package\Traits\UUID;

class Banner extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'title',
        'slug',
        'image',
        'position',
        'description',
        'note',
        'status',
        'sort_order',
        'published_at',
        'expired_at',
        'creator_id'
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = str_slug($title) . '-' . Carbon::now()->format('d-m-Y-H-i');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
