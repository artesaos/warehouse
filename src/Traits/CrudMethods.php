<?php

namespace Artesaos\Warehouse\Traits;

use Illuminate\Database\Eloquent\Model;

trait CrudMethods
{
    /**
     * Updated model data, using $data
     * The sequence performs the Model update.
     *
     * @param Model $model
     * @param array $data
     *
     * @return bool
     */
    public function update($model, array $data = [])
    {
        $this->setModelData($model, $data);

        return $this->save($model);
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

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data = [])
    {
        $model = $this->factory($data);

        $this->save($model);

        return $model;
    }

    /**
     * Performs the save method of the model
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function save($model)
    {
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
    public function delete($model)
    {
        return $model->delete();
    }
}
