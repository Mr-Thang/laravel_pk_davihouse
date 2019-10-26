<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;
use CMS\Package\Traits\UUID;

class Classify extends Model
{
    use UUID;

    const BUY_SELL              = 'mua-ban';
    const RENT                  = 'cho-thue';
    const OUTSTANDING_PROJECT   = 'du-an-noi-bat';
    const CLASSIFY_TYPES        = 'classifyTypes';

    protected $classifyProjectUtility = ['project_utility'];

    protected $groupNameUtility = [
        'tieu_dung_am_thuc' => 'Tiêu dùng ẩm thực',
        'the_thao' => 'Thể thao',
        'an_ninh_ve_sinh' => 'An ninh vệ sinh',
        'y_te_giao_duc' => 'Y tế giáo dục',
        'giai_tri' => 'Giải trí'
    ];

    protected $casts = [
        'avatar_image'       => 'array',
    ];

    protected $fillable = [
        'id',
        'title',
        'slug',
        'class_type',
        'group_name',
        'parent_id',
        'avatar_image',
        'is_basic',
        'creator_id',
        'note',
        'sort_order',
        'status'
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = str_slug($title);
    }

    public function parent()
    {
        return $this->belongsTo(Classify::class, 'parent_id')->with(['parent']);
    }

    public function children()
    {
        return $this->hasMany(Classify::class, 'parent_id')->with(['children'])->orderBy('title', 'DESC');
    }

    public function propertyTypes()
    {
        return $this->belongsToMany(
            Property::class,
            'property_type',
            'type_id',
            'property_id'
        );
    }

    public function propertyFurnitures()
    {
        return $this->belongsToMany(
            Property::class,
            'property_furniture',
            'furniture_id',
            'property_id'
        );
    }

    public function propertyUtilities()
    {
        return $this->belongsToMany(
            Property::class,
            'property_utility',
            'utility_id',
            'property_id'
        );
    }

    public function projectType()
    {
        return $this->belongsToMany(
            Project::class,
            'project_type',
            'type_id',
            'project_id'
        );
    }

    public function projectPropertyTypes()
    {
        return $this->belongsToMany(
            Project::class,
            'project_property_type',
            'type_id',
            'project_id'
        );
    }

    public function projectUtilities()
    {
        return $this->belongsToMany(
            Project::class,
            'project_utility',
            'utility_id',
            'project_id'
        );
    }

    public function scopeClassifyProjectUtility($query)
    {
        return $query->whereClassType($this->classifyProjectUtility)->where('group_name', '!=', 'co_so_vat_chat');
    }

    public function experts()
    {
        return $this->belongsToMany(
            Expert::class,
            'expert_types',
            'type_id',
            'expert_id'
        );
    }

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'post_category',
            'category_id',
            'post_id'
        );
    }

    public function scopePropertyType($query)
    {
        return $query->whereClassType('property_type');
    }

    public function scopePropertyUtility($query)
    {
        return $query->whereClassType('property_utility');
    }

    public function scopePropertyFurniture($query)
    {
        return $query->whereClassType('property_furniture');
    }

    public function getAll()
    {
        return self::whereClassType($this->class_type)->get();
    }
}
