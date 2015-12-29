<?php

namespace Artesaos\Warehouse\Contracts\Segregated;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

interface FractalAvailable
{
    /**
     * @param Model|Arrayable $item
     * @param array $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseItem($item, $meta = array());

    /**
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param array $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseCollection($collection, $meta = array());

    /**
     * @return TransformerAbstract
     */
    public function getTransformer();
}