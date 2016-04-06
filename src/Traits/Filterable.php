<?php

namespace Artesaos\Warehouse\Traits;

use Artesaos\Warehouse\AbstractQueryFilter;

trait Filterable
{
    /**
     * Filter a request.
     *
     * @param Request             $request
     * @param AbstractQueryFilter $filter
     *
     * @return Builder
     */
    public function filter(AbstractQueryFilter $filter, $query = null, $take = 15, $paginate = true)
    {
        if (null === $query) {
            $query = $this->newQuery();
        }

        $query = $filter->apply($query);

        return $this->doQuery($query, $take, $paginate);
    }
}
