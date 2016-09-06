<?php

namespace Artesaos\Warehouse\Operations;

/**
 * Trait DeleteRecords.
 */
trait DeleteRecords
{
    /**
     * Run the delete command model.
     * The goal is to enable the implementation of your business logic before the command.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function delete($model)
    {
        return $model->delete();
    }
}