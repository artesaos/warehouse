<?php

namespace Artesaos\Warehouse;

use Artesaos\Warehouse\Contracts\Segregated\CrudRepository;
use Artesaos\Warehouse\Traits\CrudMethods;

abstract class  AbstractCrudRepository extends BaseRepository implements CrudRepository
{
    use CrudMethods;
}
