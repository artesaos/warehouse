<?php namespace Artesaos\Warehouse\Contracts;

/**
 * Interface PresenterInterface
 * @package Artesaos\Warehouse\Contracts
 */
interface PresenterInterface {

    /**
     * Prepare data to present
     *
     * @param $data
     * @return mixed
     */
    public function present($data);
}