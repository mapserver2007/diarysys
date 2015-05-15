<?php
namespace Blog\Manage;

use WebStream\Core\CoreModel;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Query;
use WebStream\Annotation\Database;
use WebStream\Exception\Extend\DatabaseException;

/**
 * ManageModel
 * @Inject
 * @Database(driver="WebStream\Database\Driver\Mysql", config="config/database.yml")
 */
class ManageModel extends CoreModel
{
    /**
     * @Inject
     * @Query(file="query/diarysys-manage.xml")
     */
    public function entryById(array $params)
    {
        return $this->entryByIdQuery($params);
    }

    /**
     * @Inject
     * @Query(file="query/diarysys-manage.xml")
     */
    public function entryDeleteById(array $params)
    {
        if ($this->entryDeleteByIdQuery($params) !== 1) {
            throw new DatabaseException("Entry delete failure.");
        }
    }

    /**
     * @Inject
     * @Query(file="query/diarysys-manage.xml")
     */
    public function entryList(array $params)
    {
        $limit = ($params['page'] - 1) * $params['num'];
        $offset = $limit + $params['num'];

        return $this->entrySummaryListQuery(['limit' => $limit, 'offset' => $offset, 'query' => $params['query']]);
    }

    /**
     * @Inject
     * @Query(file="query/diarysys-manage.xml")
     */
    public function entryCount(array $params)
    {
        return $this->entryCountQuery($params);
    }
}
