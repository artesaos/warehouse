<?php

namespace Artesaos\Warehouse\Contracts\Operations;

use Artesaos\Warehouse\Contracts\Repository;

/**
 * Interface UpdateRecords.
 */
interface UpdateRecords extends Repository
{
    /**
     * Updated model data, using $data
     * The sequence performs the Model update.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     *
     * @return bool
     */
    public function update($model, array $data = []);

    /**
     * Performs the save method of the model
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function save($model);
}