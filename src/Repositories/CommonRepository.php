<?php

namespace Artesaos\Warehouse\Repositories;

use Artesaos\Warehouse\Contracts\Repositories\CommonRepository as CommonRepositoryContract;
use Artesaos\Warehouse\Traits\Repositories\BasicCrudTrait;

abstract class CommonRepository extends BaseRepository implements CommonRepositoryContract
{
    use BasicCrudTrait;
}