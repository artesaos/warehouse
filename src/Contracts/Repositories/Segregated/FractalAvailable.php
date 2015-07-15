<?php namespace Artesaos\Warehouse\Contracts\Repositories\Segregated;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

interface FractalAvailable
{
    /**
     * @param Model|Arrayable $item
     * @param array $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseItem($item, $metas = array());

    /**
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param array                                    $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseCollection($collection, $metas = array());

    /**
     * @return TransformerAbstract
     */
    public function getTransformer();
}