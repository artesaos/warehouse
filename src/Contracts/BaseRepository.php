<?php

namespace Artesaos\Warehouse\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;

interface BaseRepository
{
    /**
     * Returns all records.
     * If $take is false then brings all records
     * If $paginate is true returns Paginator instance.
     *
     * @param int  $take
     * @param bool $paginate
     *
     * @return EloquentCollection|Paginator
     */
    public function getAll($take = 15, $paginate = true);

    /**
     * Retrieves a record by his id
     * If $fail is true fires ModelNotFoundException. When no record is found.
     *
     * @param int     $id
     * @param bool $fail
     *
     * @return Model
     */
    public function findByID($id, $fail = true);

    /**
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function lists($column, $key = null);
}
