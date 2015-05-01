<?php
namespace Blog\Entry;

use WebStream\Core\CoreController;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Header;
use WebStream\Annotation\Template;

/**
 * EntryApiController
 */
class EntryApiController extends CoreController
{
    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("monthly_archive.tmpl")
     */
    public function entryMonthlyArchive()
    {
        $this->EntryApi->entryMonthlyArchive();
    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("tag_list.tmpl")
     */
    public function entryTagList()
    {
    }
}
