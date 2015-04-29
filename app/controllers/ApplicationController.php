<?php
namespace Blog\Entry;

use WebStream\Core\CoreController;
use WebStream\Annotation\Inject;
use WebStream\Annotation\ExceptionHandler;

/**
 * ApplicationController
 */
class ApplicationController extends CoreController
{
    /**
     * @Inject
     * @ExceptionHandler("WebStream\Exception\ApplicationException")
     */
    public function error($params)
    {
        // TODO エラーページ つーか@Templateが使えないのはなんとかできないものか
        var_dump($params);
    }
}
