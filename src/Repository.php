<?php

namespace Artesaos\Warehouse;

use Artesaos\Warehouse\Contracts\Repository as RepositoryContract;

abstract class Repository implements RepositoryContract
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Transformer class for repo.
     *
     * @var string
     */
    protected $transformerClass;

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param int                               $take
     * @param bool                              $paginate
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\AbstractPaginator
     */
    public function doQuery($query = null, $take = 15, $paginate = true)
    {
        if (is_null($query)) {
            $query = $this->newQuery();
        }

        if (true == $paginate) {
            return $query->paginate($take);
        }

        if ($take > 0 || false !== $take) {
            $query->take($take);
        }

        return $query->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function newQuery()
    {
        return app()->make($this->modelClass)->newQuery();
    }



    /**
     * Creates a Model object with the $data information.
     *
     * @param array $data
     *
     * @return Model
     */
    public function factory(array $data = [])
    {
        $model = $this->newQuery()->getModel()->newInstance();

        $this->setModelData($model, $data);

        return $model;
    }
}
