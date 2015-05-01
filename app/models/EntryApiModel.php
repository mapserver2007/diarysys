<?php
namespace Blog\Entry;

use WebStream\Core\CoreModel;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Query;
use WebStream\Annotation\Database;

/**
 * @Inject
 * @Database(driver="WebStream\Database\Driver\Mysql", config="config/database.yml")
 */
class EntryApiModel extends CoreModel
{
    /**
     * @Inject
     * @Query(file="query/diarysys-entry.xml")
     */
    public function entryMonthlyArchive()
    {
        return $this->entryMonthlyArchiveQuery();
    }

    /**
     * @Inject
     * @Query(file="query/diarysys-entry.xml")
     */
    public function entryTagList()
    {
        return $this->entryTagListQuery();
    }
}
