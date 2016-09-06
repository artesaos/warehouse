<?php

namespace Artesaos\Warehouse\Fractal;

use ArrayAccess;
use Artesaos\Warehouse\Contracts\FractalFactory as FractalFactoryContract;
use Artesaos\Warehouse\Fractal\Transformers\GenericTransformer;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use League\Fractal\Manager as LeagueFractalFactory;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

class FractalFactory implements FractalFactoryContract
{
    /**
     * @var LeagueFractalFactory
     */
    protected $fractal;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Container
     */
    private $app;

    /**
     * @var Response
     */
    private $response;

    /**
     * FractalFactory constructor.
     *
     * @param Request         $request
     * @param Container       $app
     * @param ResponseFactory $response
     */
    public function __construct(Request $request, Container $app, ResponseFactory $response)
    {
        $this->request = $request;
        $this->app = $app;
        $this->response = $response;
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
     * @return void
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
    }

    /**
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseFractal(ResourceAbstract $data)
    {
        $fractal = $this->getFractalFactory()->createData($data)->toArray();

        return $this->response->json($fractal);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRequestIncludes($key = 'include')
    {
        $include = $this->request->get($key, '');

        if (empty($include)) {
            return [];
        }

        $include = explode(',', $include);

        return $include;
    }

    /**
     * @return void
     */
    protected function loadFractalIncludes()
    {
        $include = $this->getRequestIncludes();

        if (!empty($include)) {
            $this->getFractalFactory()->parseIncludes($include);
        }
    }

    /**
     * @param ArrayAccess $item
     * @param             $include
     */
    protected function modelIncludes($item, $include)
    {
        if ($item instanceof Model) {
            $item->load($include);
        }
    }

    /**
     * @param ArrayAccess $collection
     * @param             $include
     */
    protected function collectionIncludes($collection, $include)
    {
        $collection->load($include);
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
     * @param array $metas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeEmptyResponse(array $metas = [])
    {
        $resource = $this->makeFractalItem([], $this->getTransformer());

        $this->addFractalMeta($resource, $metas);

        return $this->responseFractal($resource);
    }

    /**
     * @param ArrayAccess         $item
     * @param array               $meta
     * @param TransformerAbstract $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeItemResponse(ArrayAccess $item, array $meta = [], TransformerAbstract $transformer = null)
    {
        $this->loadFractalIncludes();

        $include = $this->getRequestIncludes();

        $this->modelIncludes($item, $include);

        $resource = $this->makeFractalItem($item, $this->getTransformer($transformer));

        $this->addFractalMeta($resource, $meta);

        return $this->responseFractal($resource);
    }

    /**
     * @param ArrayAccess              $collection
     * @param array                    $meta
     * @param TransformerAbstract|null $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeCollectionResponse(ArrayAccess $collection, array $meta = [], TransformerAbstract $transformer = null)
    {
        $this->loadFractalIncludes();

        $this->collectionIncludes($collection, $this->getRequestIncludes());

        if ($this->isPaged($collection)) {
            $paginator = $collection;
            $collection = $collection->getIterator();
            $resource = $this->makeFractalCollection($collection, $this->getTransformer($transformer));
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        } else {
            $resource = $this->makeFractalCollection($collection, $this->getTransformer($transformer));
        }

        $this->addFractalMeta($resource, $meta);

        return $this->responseFractal($resource);
    }
}
