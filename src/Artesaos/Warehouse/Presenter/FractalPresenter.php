<?php namespace Artesaos\Warehouse\Presenter;

use Artesaos\Warehouse\Contracts\PresenterInterface;
use Artesaos\Warehouse\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * Class FractalPresenter
 * @package Artesaos\Warehouse\Presenter
 */
abstract class FractalPresenter implements PresenterInterface {

    /**
     * @var \League\Fractal\Manager
     */
    protected $fractal;

    /**
     * @var \League\Fractal\Resource\Collection
     */
    protected $resource = null;

    public function __construct(){
        $this->fractal = new Manager();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    abstract public function getTransformer();

    /**
     * Prepare data to present
     *
     * @param $data
     * @return mixed
     */
    public function present($data)
    {
        if( $data instanceof EloquentCollection )
        {
            $this->resource = $this->transformCollection($data);
        }
        elseif( $data instanceof AbstractPaginator )
        {
            $this->resource = $this->transformPaginator($data);
        }
        else
        {
            $this->resource = $this->transformItem($data);
        }

        return $this->fractal->createData($this->resource)->toArray();
    }

    /**
     * @param $data
     * @return Item
     */
    protected function transformItem($data){
        return new Item($data, $this->getTransformer() );
    }

    /**
     * @param $data
     * @return \League\Fractal\Resource\Collection
     */
    protected function transformCollection($data){
        return new Collection($data, $this->getTransformer() );
    }

    /**
     * @param AbstractPaginator|LengthAwarePaginator|Paginator $paginator
     * @return \League\Fractal\Resource\Collection
     */
    protected function transformPaginator($paginator){
        $collection = $paginator->getCollection();
        $resource = new Collection($collection, $this->getTransformer() );
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $resource;
    }
}