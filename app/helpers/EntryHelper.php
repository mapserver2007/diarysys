<?php
namespace Blog\Entry;

use WebStream\Core\CoreHelper;
use WebStream\Module\Utility;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class EntryHelper extends CoreHelper
{
    use Utility;

    public function getEntryBox(array $entryList)
    {
        $html = "";

        foreach ($entryList as $entry) {
            $title = safetyOut($entry['title']);
            $description = safetyOut($entry['description']);

            $html .= <<< HELPER
<div class="entry_box">
    <div class="entry_image">
        <a href="/"><img src="http://images.gunosy.com/4/29/5e5b5e90b1832c66130743eb3c2b979c_large.jpg"/></a>
    </div>
    <h2 class="trim">$title</h2>
    <div class="description">$description</div>
</div>
HELPER;
        }

        return $html;
    }

    public function getPaginate($currentPage, $maxPerPage, $entryCount, $pathInfo)
    {
        if ($maxPerPage >= $entryCount) {
            return;
        }

        $list = range(1, $entryCount);
        $adapter = new ArrayAdapter($list);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($maxPerPage)->setCurrentPage($currentPage);

        $view = new DefaultView();
        $options = ['previous_message' => '«Preview', 'next_message' => 'Next»'];
        $routeGenerator = function ($page) use ($pathInfo) {
            return $pathInfo . '?p=' . $page;
        };

        return $view->render($pagerfanta, $routeGenerator, $options);
    }
}
