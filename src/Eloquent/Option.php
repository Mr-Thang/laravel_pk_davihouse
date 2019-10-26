<?php

namespace CMS\Package\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';

    protected $fillable = [
        'id',
        'option_name',
        'option_value',
        'autoload'
    ];

    public $timestamps = false;

    public static function add($key, $val)
    {
        return self::create(['option_name' => $key, 'option_value' => $val]) ? $val : false;
    }

    public static function set($key, $val)
    {
        if ($setting = self::getAllSettings()->where('option_name', $key)->first()) {
            return $setting->update([
                'option_name' => $key,
                'option_value' => $val,
            ]) ? $val : false;
        }

        return self::add($key, $val);
    }

    public static function getAllSettings()
    {
        return self::all();
    }

    public function scopeFilterSearchPrice()
    {
        return collect([
            ['price_min' => 0, 'price_max' => 0, 'slug' => null, 'name' => 'Tất cả mức giá'],
            ['price_min' => 0, 'price_max' => 10000000, 'slug' => 'duoi-10-trieu', 'name' => 'Dưới 10 triệu'],
            ['price_min' => 10000000, 'price_max' => 20000000,  'slug' => 'tu-10-trieu-den-20-trieu', 'name' => 'Từ 10 triệu đến 20 triệu'],
            ['price_min' => 20000000, 'price_max' => 50000000, 'slug'  => 'tu-20-trieu-den-50-trieu', 'name' => 'Từ 20 triệu đến 50 triệu'],
            ['price_min' => 50000000, 'price_max' => 100000000, 'slug' => 'tu-50-trieu-den-100-trieu', 'name' => 'Từ 50 triệu đến 100 triệu'],
            ['price_min' => 100000000, 'price_max' => 200000000, 'slug' => 'tu-100-trieu-den-200-trieu', 'name' => 'Từ 100 triệu đến 200 triệu'],
            ['price_min' => 200000000, 'price_max' => 500000000, 'slug' => 'tu-200-trieu-den-500-trieu', 'name' => 'Từ 200 triệu đến 500 triệu'],
            ['price_min' => 500000000, 'price_max' => 1000000000, 'slug' => 'tu-500-trieu-den-1-ty', 'name' => 'Từ 500 triệu đến 1 tỷ'],
            ['price_min' => 1000000000, 'price_max' => 2000000000, 'slug' => 'tu-1-ty-den-2-ty', 'name' => 'Từ 1 tỷ đến 2 tỷ'],
            ['price_min' => 2000000000, 'price_max' => 5000000000, 'slug' => 'tu-2-ty-den-5-ty', 'name' => 'Từ 2 tỷ đến 5 tỷ'],
            ['price_min' => 5000000000, 'price_max' => 10000000000, 'slug' => 'tu-5-ty-den-10-ty', 'name' => 'Từ 5 tỷ đến 10 tỷ'],
            ['price_min' => 10000000000, 'price_max' => 20000000000, 'slug' => 'tu-10-ty-den-20-ty', 'name' => 'Từ 10 tỷ đến 20 tỷ'],
            ['price_min' => 20000000000, 'price_max' => 50000000000, 'slug' => 'tu-20-ty-den-50-ty', 'name' => 'Từ 20 tỷ đến 50 tỷ'],
            ['price_min' => 50000000000, 'price_max' => 100000000000, 'slug' => 'tu-50-ty-den-100-ty', 'name' => 'Từ 50 tỷ đến 100 tỷ'],
        ]);
    }

    public function scopeFilterSearchAcreage()
    {
        return collect([
            ['acreage_min' => 0, 'acreage_max' => 0, 'slug' => null, 'name' => 'Tất cả diện tích'],
            ['acreage_min' => 0, 'acreage_max' => 30, 'slug' => 'duoi-30-m2', 'name' => 'Dưới 30 m2'],
            ['acreage_min' => 30, 'acreage_max' => 50, 'slug'  => 'tu-30-m2-den-50-m2', 'name' => 'Từ 30 m2 đến 50 m2'],
            ['acreage_min' => 50, 'acreage_max' => 70, 'slug' => 'tu-50-m2-den-70-m2', 'name' => 'Từ 50 m2 đến 70 m2'],
            ['acreage_min' => 70, 'acreage_max' => 100, 'slug' => 'tu-70-m2-den-100-m2', 'name' => 'Từ 70 m2 đến 100 m2'],
            ['acreage_min' => 100, 'acreage_max' => 150, 'slug' => 'tu-100-m2-den-150-m2', 'name' => 'Từ 100 m2 đến 150 m2'],
            ['acreage_min' => 150, 'acreage_max' => 300, 'slug' => 'tu-150-m2-den-300-m2', 'name' => 'Từ 150 m2 đến 300 m2'],
            ['acreage_min' => 300, 'acreage_max' => 500, 'slug' => 'tu-300-m2-den-500-m2', 'name' => 'Từ 300 m2 đến 500 m2'],
            ['acreage_min' => 500, 'acreage_max' => 100000, 'slug' => 'tu-500-m2-tro-len', 'name' => 'Từ 500 m2 trở lên'],
        ]);
    }
}
