<?php

namespace CMS\Package\Repositories\Role;

use Illuminate\Support\Facades\DB;
use CMS\Package\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    { }

    public function createMany(array $attribute, $relationshipId)
    {
        try {

            DB::beginTransaction();

            $eloquent = $this->create($attribute);
            $eloquent->permissions()->attach($relationshipId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function updateMany(array $attribute, $id, $relationshipId)
    {
        try {
            DB::beginTransaction();
            $eloquent = $this->findOrFail($id);

            $eloquent->update($attribute);
            $eloquent->permissions()->sync($relationshipId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
