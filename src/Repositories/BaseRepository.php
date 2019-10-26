<?php

namespace CMS\Package\Repositories;

use Carbon\Carbon;
use CMS\Package\Eloquent\Classify;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

abstract class BaseRepository
{
    protected $_model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function model();

    public function setModel()
    {
        $this->_model = app()->make(
            $this->model()
        );
    }

    public function all()
    {
        return $this->_model->all();
    }

    public function dataTable()
    {
        $eloquent = $this->_model->query();

        return DataTables::of($eloquent)
            ->make(true);
    }

    public function dataTableRelationship($relationship)
    {
        $eloquent = $this->_model->with($relationship)->get();

        return DataTables::of($eloquent)
            ->make(true);
    }

    public function paginate($limit = null, $orderBy, $valueOrderBy)
    {
        return $this->_model->where([
            ['published_at', '<=', Carbon::now()],
            ['expired_at', '>=', Carbon::now()],
            ['status', 'published'],
        ])->orderBy($orderBy, $valueOrderBy)->paginate($limit);
    }

    public function orderBy($column, $direction)
    {
        return $this->_model->orderBy($column, $direction)->get();
    }

    public function where($column, $value)
    {
        return $this->_model->where($column, $value)->get();
    }

    public function findOrFail($id)
    {
        try {
            return $this->_model->findOrFail($id);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findBySlugOrFail($slug)
    {
        try {
            return $this->_model->whereSlug($slug)->firstOrFail();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function create(array $attribute)
    {
        return $this->_model->create($attribute);
    }

    public function updateId(array $attribute, $id)
    {
        $eloquent = $this->findOrFail($id);

        return $eloquent->update($attribute);
    }

    public function updateSlug(array $attribute, $slug)
    {
        $eloquent = $this->findBySlugOrFail($slug);

        return $eloquent->update($attribute);
    }

    public function destroyId($id)
    {
        $eloquent = $this->findOrFail($id);

        return $eloquent->destroy();
    }

    public function destroySlug($slug)
    {
        $eloquent = $this->findBySlugOrFail($slug);

        return $eloquent->destroy();
    }

    public function getWhere($columnWhere, $valueWhere)
    {
        return $this->_model->where($columnWhere, $valueWhere)->get();
    }

    public function pluck($column1, $column2)
    {
        return $this->_model->pluck($column1, $column2)->all();
    }

    public function paginateWhereStatus($limit = null, $whereColumn, $valueWhere, $orderBy, $valueOrderBy)
    {
        return $this->_model->where($whereColumn, $valueWhere)->orderBy($orderBy, $valueOrderBy)->paginate($limit);
    }

    public function whereIn($column, array $attributes)
    {
        return $this->_model->whereIn($column, $attributes)->get();
    }

    public function getWhereStatusNull($column, $direction)
    {
        return $this->_model->where('status', '!=', null)->orderBy($column, $direction)->get();
    }

    public function descending($slug)
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

    public function takeHasRelationship(array $whereMulti = null, $relationship, $valueSlug, $limit = null, array $select = ['*'])
    {
        return $this->_model->where($whereMulti)->whereHas($relationship, function ($query) use ($valueSlug) {
            $query->whereIn('slug', $this->descending($valueSlug));
        })->select($select)->get()->take($limit);
    }

    public function whereHasRelationship($relationship, $valueSlug = null, $limit = null)
    {
        return $this->_model->where([
            ['published_at', '<=', Carbon::now()],
            ['expired_at', '>=', Carbon::now()],
            ['status', 'published'],
        ])->whereHas($relationship, function ($query) use ($valueSlug) {
            if ($valueSlug) {
                $query->whereIn('slug', $valueSlug);
            }
        })->paginate($limit);
    }

    public function take($columnOrderBy = null)
    {
        return $this->_model->whereDefault()->latest($columnOrderBy)->take(config('repository.pagination.take'))->get();
    }

    public function getPaginateWhereCount($relationship, $columnWhere, $valueWhere, $limit = null)
    {
        return $this->_model->where($columnWhere, $valueWhere)->withCount($relationship)->orderBy('properties_count', 'DESC')->take($limit)->get();
    }

    public function uploadFile($file, array $sizes)
    {
        try {
            if ($file) {
                $types = ['v1-', 'v1-'];

                $targetPath = public_path('vendor/store/images/' . Carbon::now()->format('Y/m/d') . '/');

                $ext = $file->getClientOriginalExtension();

                $nameFile = Str::uuid()->toString() . '.' . $ext;

                $original = array_shift($types) . $nameFile;

                foreach ($sizes as $key => $size) {
                    if (!\File::exists(public_path('vendor/store/images/' . Carbon::now()->format('Y/m/d') . '/' . $size[0] . 'x' . $size[1]))) {
                        $path = public_path('vendor/store/images/' . Carbon::now()->format('Y/m/d') . '/' . $size[0] . 'x' . $size[1]);
                        \File::makeDirectory($path, 0755, true);
                    }
                }

                $file->move($targetPath, $original);

                $heightImageResize = round(960 * ((\Image::make($targetPath . $original)->height()) / (\Image::make($targetPath . $original)->width())));

                if (\Image::make($targetPath . $original)->width() > 960) {
                    \Image::make($targetPath . $original)
                        ->fit(960, $heightImageResize)
                        ->save($targetPath . $original);
                }

                foreach ($sizes as $size) {
                    foreach ($types as $key => $type) {
                        $targetPathNew = public_path('vendor/store/images/' . Carbon::now()->format('Y/m/d') . '/' . $size[0] . 'x' . $size[1] . '/');
                        $newName =  $type . $nameFile;
                        \File::copy($targetPath . $original, $targetPathNew . $newName);
                        \Image::make($targetPathNew . $newName)
                            ->fit($size[0], $size[1])
                            ->save($targetPathNew . $newName);
                    }
                }

                return $original;
            }
        } catch (\Exception $e) {
            return back();
        }
    }

    public function wherePluck($slug, $columns)
    {
        return $this->_model->whereSlug($slug)->pluck($columns)->first();
    }
}
