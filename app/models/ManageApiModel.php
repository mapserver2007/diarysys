<?php
namespace Blog\Manage;

use WebStream\Core\CoreModel;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Query;
use WebStream\Annotation\Database;

/**
 * @Inject
 * @Database(driver="WebStream\Database\Driver\Mysql", config="config/database.yml")
 */
class ManageApiModel extends CoreModel
{
    /**
     * @Inject
     * @Query(file="query/diarysys-manage.xml")
     */
    public function tagList()
    {
        return $this->tagListQuery();
    }

    /**
     * @Inject
     * @Query(file="query/diarysys-manage.xml")
     */
    public function category()
    {
        return $this->categoryQuery();
    }
}
