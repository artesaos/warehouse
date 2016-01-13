<?php

namespace Artesaos\Warehouse\Contracts;

use ArrayAccess;
use League\Fractal\TransformerAbstract;

interface FractalFactory
{
    /**
     * @param ArrayAccess              $collection
     * @param array                    $meta
     * @param TransformerAbstract|null $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeCollectionResponse(ArrayAccess $collection, array $meta = [], TransformerAbstract $transformer = null);

    /**
     * @param ArrayAccess              $item
     * @param array                    $meta
     * @param TransformerAbstract|null $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeItemResponse(ArrayAccess $item, array $meta = [], TransformerAbstract $transformer = null);

    /**
     * @param array $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeEmptyResponse(array $meta = []);

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRequestIncludes($key = 'include');
}
