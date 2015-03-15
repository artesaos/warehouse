<?php namespace Artesaos\Warehouse\Criteria;

use Illuminate\Http\Request;
use Artesaos\Warehouse\Contracts\CriteriaInterface;
use Artesaos\Warehouse\Contracts\RepositoryInterface;

/**
 * Class RequestCriteria
 * @package Artesaos\Warehouse\Criteria
 */
class RequestCriteria implements CriteriaInterface {

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Apply criteria in query repository
     *
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $fieldsSearchable   = $repository->getFieldsSearchable();
        $search             = $this->request->get( config('warehouse.criteria.params.search','search') , null);
        $searchFields       = $this->request->get( config('warehouse.criteria.params.searchFields','searchFields') , null);
        $filter             = $this->request->get( config('warehouse.criteria.params.filter','filter') , null);
        $orderBy            = $this->request->get( config('warehouse.criteria.params.orderBy','orderBy') , null);
        $sortedBy           = $this->request->get( config('warehouse.criteria.params.sortedBy','sortedBy') , 'asc');
        $sortedBy           = !empty($sortedBy) ? $sortedBy : 'asc';
        
        if( $search && is_array($fieldsSearchable) && count($fieldsSearchable) )
        {

            $searchFields       = is_array($searchFields) || is_null($searchFields) ? $searchFields : explode(';',$searchFields);
            $fields             = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField       = true;
            $searchData         = array();
            $modelForceAndWhere = false;

            foreach($fields as $field=>$condition)
            {
                if(is_numeric($field)){
                    $field = $condition;
                    $condition = "=";
                }
                $condition  = trim(strtolower($condition));
                if( isset($searchData[$field]) )
                {
                    $value = $condition == "like" ? "%{$searchData[$field]}%" : $searchData[$field];
                }
                else
                {
                    $value = $condition == "like" ? "%{$search}%" : $search;
                }
                if( $isFirstField || $modelForceAndWhere )
                {
                    $model = $model->where($field,$condition,$value);
                    $isFirstField = false;
                }
                else
                {
                    $model = $model->orWhere($field,$condition,$value);
                }
            }
        }

        if( isset($orderBy) && !empty($orderBy) )
        {
            $model = $model->orderBy($orderBy, $sortedBy);
        }

        if( isset($filter) && !empty($filter) )
        {
            if( is_string($filter) )
            {
                $filter = explode(';', $filter);
            }

            $model = $model->select($filter);
        }

        return $model;
    }


    protected function parserFieldsSearch(array $fields = array(), array $searchFields =  null)
    {
        if( !is_null($searchFields) && count($searchFields) )
        {
            $acceptedConditions = config('warehouse.criteria.acceptedConditions', array('=','like') );
            $originalFields     = $fields;
            $fields = [];

            foreach($searchFields as $index => $field)
            {
                $field_parts = explode(':', $field);
                $_index = array_search($field_parts[0], $originalFields);

                if( count($field_parts) == 2 )
                {
                    if( in_array($field_parts[1],$acceptedConditions) )
                    {
                        unset($originalFields[$_index]);
                        $field                  = $field_parts[0];
                        $condition              = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index]   = $field;
                    }
                }
            }

            foreach($originalFields as $field=>$condition)
            {
                if(is_numeric($field)){
                    $field = $condition;
                    $condition = "=";
                }
                if( in_array($field, $searchFields) )
                {
                    $fields[$field] = $condition;
                }
            }

            if( count($fields) == 0 ){
                throw new \Exception( trans('warehouse::criteria.fields_not_accepted', array('field'=>implode(',', $searchFields))) );
            }

        }

        return $fields;
    }
}