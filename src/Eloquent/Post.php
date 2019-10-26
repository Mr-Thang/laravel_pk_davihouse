<?php

namespace CMS\Package\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Post extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'parent_id',
        'title',
        'slug',
        'post_type',
        'view_name',
        'excerpt',
        'content',
        'images',
        'password',
        'parameters',
        'Descending',
        'note',
        'status',
        'sort_order',
        'creator_id',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'images'       => 'array',
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = str_slug($title);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(
            Classify::class,
            'post_category',
            'post_id',
            'category_id'
        );
    }

    public function scopeWhereDefault($query)
    {
        $query->where([
            ['published_at', '<=', Carbon::now()],
            ['expired_at', '>=', Carbon::now()],
            ['status', 'published']
        ]);
    }
}
