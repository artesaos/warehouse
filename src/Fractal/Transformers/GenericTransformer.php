<?php

namespace Artesaos\Warehouse\Fractal\Transformers;

use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\TransformerAbstract;

class GenericTransformer extends TransformerAbstract
{
    /**
     * @param mixed $data
     *
     * @return array
     */
    public function transform($data)
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_object($data) && $data instanceof Arrayable) {
            return $data->toArray();
        }

        return (array) $data;
    }
}
