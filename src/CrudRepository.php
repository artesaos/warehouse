<?php

namespace Artesaos\Warehouse;

use Artesaos\Warehouse\Contracts\Repository as RepositoryContract;
use Artesaos\Warehouse\Contracts\Operations\CreateRecords as CreateRecordsContract;
use Artesaos\Warehouse\Operations\CreateRecords;
use Artesaos\Warehouse\Contracts\Operations\ReadRecords as ReadRecordsContract;
use Artesaos\Warehouse\Operations\DeleteRecords;
use Artesaos\Warehouse\Operations\ReadRecords;
use Artesaos\Warehouse\Contracts\Operations\UpdateRecords as UpdateRecordsContract;
use Artesaos\Warehouse\Operations\UpdateRecords;
use Artesaos\Warehouse\Contracts\Operations\DeleteRecords as DeleteRecordsContract;


/**
 * Class CrudRepository.
 */
abstract class CrudRepository extends Repository implements RepositoryContract,
                                                   ReadRecordsContract,
                                                   CreateRecordsContract,
                                                   UpdateRecordsContract,
                                                   DeleteRecordsContract
{
    use CreateRecords,
        ReadRecords,
        UpdateRecords,
        DeleteRecords;
}