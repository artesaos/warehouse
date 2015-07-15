<?php namespace Artesaos\Warehouse\Contracts;

use ArrayAccess;
use League\Fractal\TransformerAbstract;

interface FractalFactory
{
    /**
     * @param ArrayAccess $collection
     * @param array       $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeCollectionResponse(ArrayAccess $collection, array $metas = array(), TransformerAbstract $transformer = null);

    /**
     * @param ArrayAccess $item
     * @param array       $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeItemResponse(ArrayAccess $item, array $metas = [], TransformerAbstract $transformer = null);

    /**
     * @param array $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeEmptyResponse(array $metas = array());

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRequestIncludes($key = 'include');
}