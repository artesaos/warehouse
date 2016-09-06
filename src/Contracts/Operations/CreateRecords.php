<?php

namespace Artesaos\Warehouse\Contracts\Operations;

use Artesaos\Warehouse\Contracts\Repository;

/**
 * Interface CreateRecords.
 */
interface CreateRecords extends Repository
{
    /**
     * Creates a Model object with the $data information.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data = []);

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