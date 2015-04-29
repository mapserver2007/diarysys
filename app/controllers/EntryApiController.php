<?php
namespace Blog\Entry;

use WebStream\Core\CoreController;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Header;

/**
 * EntryApiController
 */
class EntryApiController extends CoreController
{
    /**
     * @Inject
     * @Header(contentType="json", allowMethod="GET")
     */
    public function entryMonthlyCount()
    {
        echo json_encode(["monthly" => $this->EntryApi->entryMonthlyCount()]);
    }

    /**
     * @Inject
     * @Header(contentType="json", allowMethod="GET")
     */
    public function entryTagCount()
    {
        echo json_encode(["tag" => $this->EntryApi->entryTagCount()]);
    }
}
