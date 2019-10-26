<?php

namespace CMS\Package\Repositories\User;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use CMS\Package\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    public function model()
    { }

    public function createMany(array $attribute, $relationshipId)
    {
        try {
            DB::beginTransaction();

            $eloquent = $this->create($attribute);
            $eloquent->roles()->attach($relationshipId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function updateRole($id, $relationshipId = null)
    {
        $eloquent = $this->findOrFail($id);
        return $eloquent->roles()->sync($relationshipId);
    }

    public function getData($eloquent)
    {
        return DataTables::of($eloquent)

            ->editColumn('status', function ($eloquent) {
                if ($eloquent->status == 'enable') {
                    return '<span class="text-success"><i class="fas fa-check"></i> Hoạt động</span>';
                } else {
                    return '<span class="text-secondary">Tạm khóa</span>';
                }
            })
            ->editColumn('created_at', function ($eloquent) {
                return $eloquent->created_at->format('d/m/Y');
            })
            ->addColumn('avatar', function ($eloquent) {
                $default = asset('vendor/medias/views/default_avatar.png');

                if ($eloquent->profile['avatar']) { } else {
                    return '<img class="default__avatar" src="' . $default . '" title="' . $eloquent->profile['full_name'] . '">';
                }
            })
            ->addColumn('roles', function ($eloquent) {
                return $eloquent->roles->map(function ($role) {
                    return '<span class="text-primary">' . $role->name . '</span>';
                })->implode('<br>');
            })
            ->addColumn('actions', function ($eloquent) {
                return
                    '<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Chọn chức năng
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' .
                    \Form::open() .
                    \Form::submit('Delete', array('class' => 'dropdown-item')) .
                    \Form::close() .
                    '</div>';
            })

            ->rawColumns(['roles', 'status', 'avatar', 'actions'])
            ->make(true);
    }

    public function login(array $attributes, $remember)
    {
        if (Auth::guard('sys')->attempt($attributes, $remember)) {
            return true;
        }
        return false;
    }

    public function logout()
    {
        if (Auth::guard('sys')->check()) {
            Auth::guard('sys')->logout();
            return redirect()->route('sys.get.login');
        }
    }
}
