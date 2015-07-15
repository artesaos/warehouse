<?php namespace Artesaos\Warehouse\Transformers;

use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\TransformerAbstract;

class GenericTransformer extends TransformerAbstract
{
    public function transform($data)
    {
        if (is_array($data)):
            return $data;
        endif;
        if (is_object($data)):
            if ($data instanceof Arrayable):
                return $data->toArray();
            endif;
        endif;

        return (array)$data;
    }
}