<?php

namespace CMS\Package\Repositories;

interface BaseInterface
{
    public function all();

    public function dataTable();

    public function dataTableRelationship($relationship);

    public function paginate($limit = null, $orderBy, $valueOrderBy);

    public function orderBy($column, $direction);

    public function where($column, $value);

    public function findOrFail($id);

    public function findBySlugOrFail($slug);

    public function create(array $attribute);

    public function updateId(array $attribute, $id);

    public function updateSlug(array $attribute, $slug);

    public function destroyId($id);

    public function destroySlug($slug);

    public function getWhere($columnWhere, $valueWhere);

    public function pluck($column1, $column2);

    public function paginateWhereStatus($limit = null, $whereColumn, $valueWhere, $orderBy, $valueOrderBy);

    public function whereIn($column, array $attributes);

    public function getWhereStatusNull($orderBy, $valueOrderBy);

    public function descending($slug);

    public function takeHasRelationship(array $whereMulti = null, $relationship, $valueSlug, $limit = null, array $select = ['*']);

    public function whereHasRelationship($relationship, $valueSlug = null, $limit = null);

    public function take($columnOrderBy = null);

    public function getPaginateWhereCount($relationship, $columnWhere, $valueWhere, $limit = null);

    public function uploadFile($file, array $sizes);

    public function wherePluck($slug, $columns);
}
