<?php namespace Artesaos\Warehouse\Repositories;

use Artesaos\Warehouse\Contracts\Repositories\Common as CommonContract;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use League\Fractal\TransformerAbstract;

abstract class CommonRepository implements CommonContract
{
    /**
     * Model class for repo
     *
     * @var string
     */
    protected $modelClass;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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
     * Updated model data, using $data
     * If $data is an empty array using the Request data
     * The sequence performs the Model update
     *
     * @param Model $model
     * @param array $data
     *
     * @return bool
     */
    public function update(Model &$model, array $data = array())
    {
        if (empty($data)) $data = $this->request->all();

        $model->fill($data);

        return $this->save($model);
    }

    /**
     * Creates a Model object with the $data information
     * If $data is an empty array creates an object with the Request data
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data = [])
    {
        $data = (empty($data)) ? $this->request->all() : $data;

        return $this->newQuery()->getModel()->newInstance($data);
    }

    /**
     * Performs the save method of the model
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function save(Model &$model)
    {
        # your logic
        return $model->save();
    }

    /**
     * Run the delete command model.
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function delete(Model &$model)
    {
        # your logic
        return $model->delete();
    }

    /**
     * @return EloquentQueryBuilder|QueryBuilder
     */
    protected function newQuery()
    {
        return app()->make($this->modelClass)->newQuery();
    }
}