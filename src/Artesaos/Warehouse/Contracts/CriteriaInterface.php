<?php namespace Artesaos\Warehouse\Contracts;

/**
 * Interface CriteriaInterface
 * @package Artesaos\Warehouse\Contracts
 */
interface CriteriaInterface {

    /**
     * Apply criteria in query repository
     *
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);

}