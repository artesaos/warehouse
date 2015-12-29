<?php

namespace Artesaos\Warehouse\Traits;

use Artesaos\Warehouse\Contracts\FractalFactory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

trait ImplementsFractal
{
    /**
     * @return FractalFactory
     */
    protected function getFractalFactory()
    {
        return app(FractalFactory::class);
    }

    /**
     * @param Model|Arrayable $item
     * @param array           $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseItem($item, $meta = array())
    {
        return $this->getFractalFactory()->makeItemResponse($item, $meta, $this->getTransformer());
    }

    /**
     * @param Collection $collection
     * @param array      $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeResponseCollection($collection, $meta = array())
    {
        return $this->getFractalFactory()->makeCollectionResponse($collection, $meta, $this->getTransformer());
    }

    /**
     * @return TransformerAbstract
     */
    protected function getTransformer()
    {
        return app($this->transformerClass);
    }
}