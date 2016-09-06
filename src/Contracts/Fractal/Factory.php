<?php

namespace Artesaos\Warehouse\Contracts\Fractal;

use ArrayAccess;
use League\Fractal\TransformerAbstract;

/**
 * Interface Factory.
 */
interface Factory
{
    /**
     * @param \ArrayAccess              $collection
     * @param array                    $meta
     * @param TransformerAbstract|null $transformer
     *
     * @return \Illuminate\Support\Collection
     */
    public function makeCollection(ArrayAccess $collection, array $meta = [], TransformerAbstract $transformer = null);

    /**
     * @param ArrayAccess              $item
     * @param array                    $meta
     * @param TransformerAbstract|null $transformer
     *
     * @return \ArrayAccess
     */
    public function makeItem(ArrayAccess $item, array $meta = [], TransformerAbstract $transformer = null);
}