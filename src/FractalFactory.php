<?php namespace Artesaos\Warehouse;

use Artesaos\Warehouse\Contracts\FractalFactory as FractalFactoryContract;
use Artesaos\Warehouse\Transformers\GenericTransformer;
use ArrayAccess;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager as LeagueFractalFactory;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;
use Illuminate\Http\Request;

class FractalFactory implements FractalFactoryContract
{

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @var LeagueFractalFactory
     */
    private $fractal;

    /**
     * @param \Illuminate\Database\Eloquent\Model|Paginator $collection
     *
     * @return bool
     */
    protected function isPaged($collection)
    {
        return (is_a($collection, 'Illuminate\Contracts\Pagination\LengthAwarePaginator'));
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
        if (!$this->fractal):
            $this->fractal = new LeagueFractalFactory();

            $serializer = config('warehouse.fractal.serializer', null);
            if(!empty($serializer)) {
                $this->fractal->setSerializer(app($serializer));
            }
        endif;

        return $this->fractal;
    }

    /**
     * @param ResourceAbstract $resource
     * @param string|array     $key
     * @param null             $value
     *
     * @return void
     */
    protected function addFractalMeta(ResourceAbstract &$resource, $key, $value = null)
    {
        if (is_array($key)):
            foreach ($key as $k => $v):
                $resource->setMetaValue($k, $v);
            endforeach;
        else:
            $resource->setMetaValue($key, $value);
        endif;
    }

    /**
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseFractal(ResourceAbstract $data)
    {
        if(isset(app()['warehouse.transporter'])) {
            if (!app('warehouse.transporter')->isEmpty()) $this->addFractalMeta($data, app('warehouse.transporter')->toArray());
        }

        $fractal = $this->getFractalFactory()->createData($data)->toArray();

        return response()->json($fractal);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRequestIncludes($key = 'include')
    {
        $include = $this->request->get($key, '');

        if (empty($include)) return [];

        $include = explode(',', $include);

        return $include;
    }

    /**
     * @param $collection
     *
     * @return void
     */
    protected function loadFractalIncludes()
    {
        $include = $this->getRequestIncludes();

        if (!empty($include)) $this->getFractalFactory()->parseIncludes($include);
    }

    /**
     * @param ArrayAccess $item
     * @param             $include
     */
    protected function modelIncludes(ArrayAccess &$item, $include)
    {
        if ($item instanceof Model) $item->load($include);
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
    public function makeEmptyResponse(array $metas = array())
    {
        $resource = $this->makeFractalItem([], $this->getTransformer());

        $this->addFractalMeta($resource, $metas);

        return $this->responseFractal($resource);
    }

    /**
     * @param ArrayAccess         $item
     * @param array               $metas
     * @param TransformerAbstract $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeItemResponse(ArrayAccess $item, array $metas = [], TransformerAbstract $transformer = null)
    {
        $this->loadFractalIncludes();

        $include = $this->getRequestIncludes();

        $this->modelIncludes($item, $include);

        $resource = $this->makeFractalItem($item, $this->getTransformer($transformer));

        $this->addFractalMeta($resource, $metas);

        return $this->responseFractal($resource);
    }

    /**
     * @param ArrayAccess         $collection
     * @param array               $metas
     * @param TransformerAbstract $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeCollectionResponse(ArrayAccess $collection, array $metas = array(), TransformerAbstract $transformer = null)
    {
        $this->loadFractalIncludes();

        if ($this->isPaged($collection)):
            $paginator  = $collection;
            $collection = $collection->getIterator();
            $resource   = $this->makeFractalCollection($collection, $this->getTransformer($transformer));
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        else:
            $resource = $this->makeFractalCollection($collection, $this->getTransformer($transformer));
        endif;

        $this->addFractalMeta($resource, $metas);

        return $this->responseFractal($resource);
    }
}