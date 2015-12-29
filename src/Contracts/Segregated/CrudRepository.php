<?php

namespace Artesaos\Warehouse\Contracts\Segregated;

use Illuminate\Database\Eloquent\Model;

interface CrudRepository
{
    /**
     * Creates a Model object with the $data information.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data = []);
    /**
     * Updated model data, using $data
     * The sequence performs the Model update.
     *
     * @param Model $model
     * @param array $data
     *
     * @return bool
     */
    public function update($model, array $data = []);
    /**
     * Performs the save method of the model
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function save($model);
    /**
     * Run the delete command model.
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function delete($model);

    /**
     * Creates a Model object with the $data information.
     *
     * @param array $data
     *
     * @return Model
     */
    public function factory(array $data = []);
}
