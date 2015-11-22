<?php

namespace Artesaos\Warehouse\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * Model class for repo
     *
     * @var string
     */
    protected $modelClass;

    /**
     * @param EloquentQueryBuilder|QueryBuilder $query
     * @param int                               $take
     * @param bool                              $paginate
     *
     * @return EloquentCollection|Paginator
     */
    protected function doQuery($query = null, $take = 15, $paginate = true)
    {
        if (is_null($query)) $query = $this->newQuery();

        if (true == $paginate):
            return $query->paginate($take);
        endif;

        if ($take > 0 || false == $take) $query->take($take);

        return $query->get();
    }

    /**
     * Returns all records.
     * If $take is false then brings all records
     * If $paginate is true returns Paginator instance
     *
     * @param int  $take
     * @param bool $paginate
     *
     * @return EloquentCollection|Paginator
     */
    public function getAll($take = 15, $paginate = true)
    {
        return $this->doQuery(null, $take, $paginate);
    }


    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException
     *
     * @param int     $id
     * @param boolean $fail
     *
     * @return Model
     */
    public function findByID($id, $fail = true)
    {
        if ($fail):
            return $this->newQuery()->findOrFail($id);
        endif;

        return $this->newQuery()->find($id);
    }

    /**
     * @return EloquentQueryBuilder|QueryBuilder
     */
    protected function newQuery()
    {
        return app()->make($this->modelClass)->newQuery();
    }
}