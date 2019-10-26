<?php

namespace CMS\Package\Helpers;

use CMS\Package\Eloquent\Option;
use CMS\Package\Eloquent\Classify;
use CMS\Package\Eloquent\Province;
use CMS\Package\Eloquent\Notification;

class AppHelper
{
    public static function formatInputPrice($price)
    {
        $mPrice = static::explodeDot($price);

        if ($mPrice > 0) {
            return static::formatPrice($mPrice);
        }

        return 0;
    }

    public static function showPrice($priceMin, $priceMax)
    {
        $minPrice = static::explodeDot($priceMin);
        $maxPrice = static::explodeDot($priceMax);

        if ($minPrice > 0 && $maxPrice == 0) {
            return static::formatPrice($minPrice);
        }

        if ($maxPrice > 0 && $minPrice == 0) {
            return static::formatPrice($maxPrice);
        }

        if ($maxPrice > 0 && $minPrice > 0) {
            return static::formatPrice($minPrice) . ' - ' . static::formatPrice($maxPrice);
        }

        return 'Thoả thuận';
    }

    public static function formatPrice($price)
    {
        $priceLen = static::getLengthString($price);
        switch ($priceLen) {
                // 7 - 9 => Triệu
            case 7:
                $priceFirst  = substr($price, 0, -6);
                $priceSecond = substr($price, 1, -5);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 8:
                $priceFirst  = substr($price, 0, -6);
                $priceSecond = substr($price, 2, -5);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 9:
                $priceFirst  = substr($price, 0, -6);
                $priceSecond = substr($price, 3, -5);
                return $priceFirst . '.' . $priceSecond;
                break;
                // >= 10 => Tỷ
            case 10:
                $priceFirst  = substr($price, 0, -9);
                $priceSecond = substr($price, 1, -8);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 11:
                $priceFirst  = substr($price, 0, -9);
                $priceSecond = substr($price, 2, -8);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 12:
                $priceFirst  = substr($price, 0, -9);
                $priceSecond = substr($price, 3, -8);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 13:
                $priceFirst  = substr($price, 0, -9);
                $priceSecond = substr($price, 4, -8);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 14:
                $priceFirst  = substr($price, 0, -9);
                $priceSecond = substr($price, 5, -8);
                return $priceFirst . '.' . $priceSecond;
                break;
            case 15:
                $priceFirst  = substr($price, 0, -9);
                $priceSecond = substr($price, 6, -8);
                return $priceFirst . '.' . $priceSecond;
                break;
            default:
                return 0;
        }
    }

    public static function explodeDot($string)
    {
        $getPrice = explode('.', $string);
        if ($getPrice) {
            return $getPrice[0];
        }
        return 0;
    }

    public static function getLengthString($string)
    {
        $stringLen = strlen($string);
        return $stringLen;
    }

    public static function showPriceType($priceMin, $priceMax)
    {

        if (static::getLengthString(static::explodeDot($priceMin)) >= 10 || static::getLengthString(static::explodeDot($priceMax)) >= 10) {
            return 'Tỷ';
        }
        if (static::getLengthString(static::explodeDot($priceMin)) >= 7 || static::getLengthString(static::explodeDot($priceMax)) >= 7) {
            return 'Triệu';
        }
        return '';
    }

    public static function showAcreage($acreageMin, $acreageMax)
    {
        $minAcreage =  static::explodeDot($acreageMin);
        $maxAcreage =  static::explodeDot($acreageMax);

        if ($minAcreage > 0 && $maxAcreage == 0) {
            return $minAcreage;
        }

        if ($maxAcreage > 0 && $minAcreage == 0) {
            return $maxAcreage;
        }

        if ($minAcreage > 0 && $maxAcreage > 0) {
            return $minAcreage . ' - ' . $maxAcreage;
        }

        return $acreageMin . '0' . $acreageMax;
    }

    public static function showAcreagePrice($priceMin, $priceMax, $acreageMin, $acreageMax)
    {
        if ($priceMin >= 1000000 && $acreageMin > 0 && $priceMax == 0) {
            $divisionPrice = $priceMin / 1000000;
            $acreagePrice  = $divisionPrice / $acreageMin;
            $strLength     = static::getLengthString($acreagePrice);
            return substr($acreagePrice, 0, $strLength - ($strLength - 4));
        }
        if ($priceMax >= 1000000 && $acreageMax > 0 && $priceMin == 0) {
            $divisionPrice = $priceMax / 1000000;
            $acreagePrice  = $divisionPrice / $acreageMax;
            $strLength     = static::getLengthString($acreagePrice);
            return substr($acreagePrice, 0, $strLength - ($strLength - 4));
        }
        if ($priceMin >= 1000000 || $priceMax >= 1000000) {
            if ($acreageMin > 0 && $acreageMax > 0) {
                $totalPrice   = ($priceMin + $priceMax) / 1000000;
                $totalAcreage = $acreageMin + $acreageMax;
                $acreagePrice = $totalPrice / $totalAcreage;
                $strLength    = static::getLengthString($acreagePrice);
                return substr($acreagePrice, 0, $strLength - ($strLength - 4));
            }

            $totalPrice   = $priceMax / 1000000;
            $totalAcreage = $acreageMin + $acreageMax;
            if ($totalAcreage == 0) {
                return 0;
            }
            $acreagePrice = $totalPrice / $totalAcreage;
            $strLength    = static::getLengthString($acreagePrice);
            return substr($acreagePrice, 0, $strLength - ($strLength - 4));
        }
        return 0;
    }

    public static function showOption($key)
    {
        $eloquent = Option::where('option_name', $key)->first();

        if ($eloquent) {
            return $eloquent->option_value;
        }
        return '';
    }

    public static function getClassifyMenu()
    {
        $classify = Classify::whereNull('parent_id')->whereClassType('property_type')->orderBy('title', 'DESC')->get();

        return $classify;
    }

    public static function getClassifyPostMenu($classType)
    {
        $classify = Classify::whereClassType($classType)->orderBy('title', 'DESC')->get();

        return $classify;
    }

    public static function getClassifyExpert()
    {
        $classify = Classify::whereNull('parent_id')->whereClassType('expert_type')->orderBy('title', 'DESC')->get();
        return $classify;
    }

    public static function getClassifyLease()
    {
        $classify = Classify::whereClassType('project_type')->whereNull('parent_id')->with(['children' => function ($query) {
            $query->orderBy('title', 'DESC');
        }])->firstOrFail();

        return $classify->getRelation('children');
    }

    public static function descending($slug)
    {
        if ($slug) {
            try {
                $descending =  collect();
                $withClassify = Classify::with(['children'])->whereSlug($slug)->firstOrFail();

                $children = $withClassify->children;
                if ($children->isNotEmpty()) {
                    while ($children->count()) {
                        $child = $children->shift();
                        $descending->push($child->slug);

                        $children = $children->merge($child->children);
                    }
                    return $descending;
                } else {
                    return collect($withClassify->slug);
                }
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public static function ascendings($slug)
    {
        $ascendings = collect();

        $withClassify = Classify::with(['parent'])->whereSlug($slug)->firstOrFail();

        if ($withClassify->parent_id == null) {
            $ascendings = $withClassify->slug;
        } else {
            $classify = $withClassify;
            while ($classify->parent) {
                $ascendings = $ascendings->push($classify->parent->slug);

                if ($classify->parent) {
                    $classify = $classify->parent;
                }
            }
        }
        return $ascendings;
    }

    public static function countProvincePropertyTop($slugProvince, $relationshipWithCount, $slugClassify)
    {
        $provinceProperty = Province::whereSlug($slugProvince)->withCount([$relationshipWithCount => function ($query) use ($slugClassify) {
            $query->whereHas('classifyTypes', function ($query) use ($slugClassify) {
                $query->whereIn('slug', static::descending($slugClassify));
            });
        }])->first()['properties_count'];

        return $provinceProperty > 0 ? $provinceProperty : 0;
    }

    public static function countProvinceProjectTop($slugProvince, $relationshipWithCount)
    {
        $countProvinceProject = Province::whereSlug($slugProvince)->withCount([$relationshipWithCount])->first()['properties_count'];
        return $countProvinceProject > 0 ? $countProvinceProject : 0;
    }

    public static function firstLetterString($string)
    {
        $words = explode(" ", $string);
        $acronym = "";

        foreach ($words  as $w) {
            $acronym .= $w[0];
        }

        return $acronym;
    }

    public static function countNotificationRelationshipProject()
    {
        $countNotification = Notification::whereRelationshipProject()->count();
        if ($countNotification > 0) {
            return $countNotification;
        }
        return;
    }
}
