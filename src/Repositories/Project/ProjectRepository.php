<?php

namespace CMS\Package\Repositories\Project;

use Carbon\Carbon;
use CMS\Package\Eloquent\Classify;
use Yajra\DataTables\DataTables;
use CMS\Package\Repositories\BaseRepository;

class ProjectRepository extends BaseRepository
{
    public function model()
    { }

    public function dataTableUtility()
    {
        $eloquent = Classify::classifyProjectUtility()->get();

        return DataTables::of($eloquent)
            ->editColumn('status', function ($eloquent) {
                if ($eloquent->status == 'published') {
                    return '<span class="text-success"><i class="fas fa-check"></i> xuất bản</span>';
                }
            })
            ->addColumn('action', function ($eloquent) {
                return '
                        <a href="' . route('sys.classifies.update', $eloquent->slug) . '" class="text-primary"><i class="far fa-edit"></i></a>
                        <a href="' . route('sys.classifies.update', $eloquent->slug) . '" class="text-danger"><i class="far fa-times-circle"></i></a>
                    ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function createRelationship(array $attributes, $property_type, $project_property_type, $project_utility)
    {
        try {
            \DB::beginTransaction();
            $eloquent = $this->create($attributes);
            $eloquent->classifyTypes()->attach(Classify::whereIn('slug', $property_type)->pluck('id')->toArray());
            $eloquent->classifyPropertyTypes()->attach(Classify::whereIn('slug', $project_property_type)->pluck('id')->toArray());
            $eloquent->classifyUtilities()->attach(Classify::whereIn('slug', $project_utility)->pluck('id')->toArray());
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            return false;
        }
    }

    public function updateRelationship(array $attributes, $slug, $property_type, $project_property_type, $project_utility)
    {
        try {
            \DB::beginTransaction();
            $eloquent = $this->findBySlugOrFail($slug);
            $eloquent->update($attributes);
            $eloquent->classifyTypes()->sync(Classify::whereIn('slug', $property_type)->pluck('id')->toArray());
            $eloquent->classifyPropertyTypes()->sync(Classify::whereIn('slug', $project_property_type)->pluck('id')->toArray());
            $eloquent->classifyUtilities()->sync(Classify::whereIn('slug', $project_utility)->pluck('id')->toArray());
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            return false;
        }
    }

    public function loadData($slug, $request, $limit)
    {
        return $this->_model
            ->where([
                ['published_at', '<=', Carbon::now()],
                ['expired_at', '>=', Carbon::now()],
                ['status', 'published'],
            ])
            ->orderBySearch($request['sort'])
            ->whereClassifyType($request['danh-muc'])
            ->wherePriceMinAndMax($request['price'])
            ->whereProvince($request['tinh-thanh'])
            ->whereDistrict($request['quan-huyen'])
            ->whereAcreage($request['acreage'])
            ->whereHas('classifyTypes', function ($query) use ($slug) {
                if ($slug) {
                    $query->whereIn('slug', $slug);
                }
            })
            ->paginate($limit);
    }
}
