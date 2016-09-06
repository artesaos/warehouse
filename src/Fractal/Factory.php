<?php

namespace Artesaos\Warehouse\Fractal;

use ArrayAccess;
use Artesaos\Warehouse\Contracts\Fractal\Factory as FactoryContract;
use Artesaos\Warehouse\Fractal\Transformers\GenericTransformer;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Pagination\Paginator;
use League\Fractal\Manager as LeagueFractalFactory;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

class Factory implements FactoryContract
{
    /**
     * @var \League\Fractal\Manager
     */
    protected $fractal;

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    private $app;

    /**
     * FractalFactory constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|Paginator $collection
     *
     * @return bool
     */
    protected function isPaged($collection)
    {
        return is_a($collection, \Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
    }

    /**
     * @param ArrayAccess         $collection
     * @param TransformerAbstract $transform
     *
     * @return FractalCollection
     */
    protected function makeFractalCollection(ArrayAccess $collection, TransformerAbstract $transform)
    {
        return new FractalCollection($collection, $transform);
    }

    /**
     * @param ArrayAccess         $item
     * @param TransformerAbstract $transform
     *
     * @return FractalItem
     */
    protected function makeFractalItem(ArrayAccess $item, TransformerAbstract $transform)
    {
        return new FractalItem($item, $transform);
    }

    /**
     * @return FractalFactory
     */
    protected function getFractalFactory()
    {
        if (!$this->fractal) {
            $this->fractal = new LeagueFractalFactory();

            $serializer = $this->app['config']->get('warehouse.fractal.serializer', null);

            if (!empty($serializer)) {
                $this->fractal->setSerializer($this->app->make($serializer));
            }
        }

        return $this->fractal;
    }

    /**
     * @param ResourceAbstract $resource
     * @param string           $key
     * @param string|null      $value
     *
     * @return ResourceAbstract
     */
    protected function addFractalMeta(ResourceAbstract $resource, $key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $resource->setMetaValue($k, $v);
            }
        } else {
            $resource->setMetaValue($key, $value);
        }

        return $resource;
    }

    /**
     * @param TransformerAbstract $transformer
     *
     * @return TransformerAbstract
     */
    protected function getTransformer(TransformerAbstract $transformer = null)
    {
        return (is_null($transformer)) ? new GenericTransformer() : $transformer;
    }

    /**
     * @param ArrayAccess         $item
     * @param array               $meta
     * @param TransformerAbstract $transformer
     *
     * @return ArrayAccess
     */
    public function makeItem(ArrayAccess $item, array $meta = [], TransformerAbstract $transformer = null)
    {
        $transformerInstance = $this->getTransformer($transformer);

        $resource = $this->makeFractalItem($item, $transformerInstance);

        $resource = $this->addFractalMeta($resource, $meta);

        $fractal = $this->getFractalFactory()->createData($resource)->toArray();

        return $fractal;
    }

    /**
     * @param \ArrayAccess              $collection
     * @param array                    $meta
     * @param TransformerAbstract|null $transformer
     *
     * @return \ArrayAccess
     */
    public function makeCollection(ArrayAccess $collection, array $meta = [], TransformerAbstract $transformer = null)
    {
        if ($this->isPaged($collection)) {
            $paginator = $collection;
            $collection = $collection->getIterator();
            $resource = $this->makeFractalCollection($collection, $this->getTransformer($transformer));
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        } else {
            $resource = $this->makeFractalCollection($collection, $this->getTransformer($transformer));
        }

        $resource = $this->addFractalMeta($resource, $meta);

        $fractal = $this->getFractalFactory()->createData($resource)->toArray();

        return $fractal;
    }
}
