<?php

namespace Artesaos\Warehouse\Traits\Repositories;

use Illuminate\Database\Eloquent\Model;

trait BasicCrudTrait
{
    /**
     * Updated model data, using $data
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
}