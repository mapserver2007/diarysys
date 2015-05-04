<?php
namespace Blog\Entry;

use WebStream\Core\CoreModel;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Query;
use WebStream\Annotation\Database;

/**
 * EntryModel
 * @Inject
 * @Database(driver="WebStream\Database\Driver\Mysql", config="config/database.yml")
 */
class EntryModel extends CoreModel
{
    /**
     * @Inject
     * @Query(file="query/diarysys-entry.xml")
     */
    public function entryList(array $params)
    {
        $params = array_merge($this->getEntryParams(), $params);
        $entryList = $this->entryListQuery($params);

        return $entryList;
    }

    /**
     * @Inject
     * @Query(file="query/diarysys-entry.xml")
     */
    public function entryCount(array $params)
    {
        $params = array_merge($this->getEntryParams(), $params);
        $entryCount = $this->entryCountQuery($params);

        return $entryCount;
    }

    private function getEntryParams()
    {
        return ['entry_id' => null, 'tag_name' => null, 'category_name' => null, 'created_at' => null, 'query' => null];
    }
}
