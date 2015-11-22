<?php

namespace Artesaos\Warehouse\Contracts\Repositories\Segregated;

use Illuminate\Database\Eloquent\Model;

interface BasicCrud
{

    /**
     * Creates a Model object with the $data information
     * If $data is an empty array creates an object with the Request data
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data = []);

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
    public function update(Model &$model, array $data = []);

    /**
     * Performs the save method of the model
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function save(Model &$model);

    /**
     * Run the delete command model.
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param Model $model
     *
     * @return bool
     */
    public function delete(Model &$model);
}