<?php

namespace Artesaos\Warehouse\Contracts\Operations;

use Artesaos\Warehouse\Contracts\Repository;

/**
 * Interface TransformRecords.
 */
interface TransformRecords extends Repository
{
    /**
     * @param \ArrayAccess $item
     * @param array           $meta
     *
     * @return \ArrayAccess
     */
    public function transformItem($item, $meta = []);

    /**
     * @param \Illuminate\Support\Collection $collection
     * @param array                                    $meta
     *
     * @return \ArrayAccess
     */
    public function transformCollection($collection, $meta = []);
}