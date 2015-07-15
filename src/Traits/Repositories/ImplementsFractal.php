<?php namespace Artesaos\Warehouse\Traits\Repositories;

use Artesaos\Warehouse\Contracts\FractalFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ImplementsFractal
{
    /**
     * @return FractalFactory
     */
    protected function getFractalFactory()
    {
        return app('Artesaos\Warehouse\Contracts\FractalFactory');
    }

    /**
     * @param Model|Arrayable $item
     * @param array           $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseItem($item, $metas = array())
    {
        return $this->getFractalFactory()->makeItemResponse($item, $metas, $this->getTransformer());
    }

    /**
     * @param Collection $collection
     * @param array      $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseCollection($collection, $metas = array())
    {
        return $this->getFractalFactory()->makeCollectionResponse($collection, $metas, $this->getTransformer());
    }
}