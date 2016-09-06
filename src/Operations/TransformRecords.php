<?php

namespace Artesaos\Warehouse\Operations;

use Artesaos\Warehouse\Contracts\Fractal\Factory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;


/**
 * Trait TransformRecords.
 */
trait TransformRecords
{
    /**
     * @return Factory
     */
    protected function getFractalFactory()
    {
        return app(Factory::class);
    }

    /**
     * @param Model|Arrayable $item
     * @param array           $meta
     *
     * @return \ArrayAccess
     */
    public function transformItem($item, $meta = [])
    {
        return $this->getFractalFactory()->makeItem($item, $meta, $this->getTransformer());
    }

    /**
     * @param Collection $collection
     * @param array      $meta
     *
     * @return Collection
     */
    public function transformCollection($collection, $meta = [])
    {
        return $this->getFractalFactory()->makeCollection($collection, $meta, $this->getTransformer());
    }

    /**
     * @return TransformerAbstract
     */
    protected function getTransformer()
    {
        return app($this->transformerClass);
    }
}