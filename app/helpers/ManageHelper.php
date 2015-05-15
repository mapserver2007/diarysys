<?php
namespace Blog\Manage;

use WebStream\Core\CoreHelper;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class ManageHelper extends CoreHelper
{
    public function getEntry($entryList)
    {
        if (empty($entryList)) {
            return;
        }

        $entry = reset($entryList);
        $title = safetyOut($entry['title']);
        $description = safetyOut($entry['description']);
        $createdAt = safetyOut($entry['createdAt']);
        $categoryName = safetyOut($entry['categoryName']);

        $tagMapList = $entry['tagMapList'];
        $tagHtml = "";
        foreach ($tagMapList as $id => $name) {
            $tagName = safetyOut($name);
            $tagHtml .= <<< TAG
        <span><a href="/diarysys/tag/${tagName}">${tagName}</a></span>
TAG;
        }

        return <<< HELPER
<div id="entry">
<div class="entry_info">
    <p><a href="/diarysys/category/${categoryName}">${categoryName}</a></p>
    <h2>${title}</h2>
    <span class="entry_tag">
        ${tagHtml}
    </span>
    <span class="entry_date">${createdAt}</span>
</div>
<div class="entry_description">
${description}
</div>
</div>
HELPER;
    }

    public function getEntryList($entryList)
    {
        $html = "";

        foreach ($entryList as $entry) {
            $id = $entry["id"];
            $title = $entry["title"];
            $created_at = $entry["created_at"];

            $html .= <<< HELPER
<tr>
    <td><a href="/diarysys/entry/$id" target="_blank">$title</a></td>
    <td>$created_at</td>
    <td>
        <form action="/diarysys/manage/entry_delete/confirm" method="post">
            <input type="hidden" name="entry_id" value="$id"/>
            <input type="submit" class="btn btn-info btn-xs" value="削除"/>
        </form>
    </td>
</tr>
HELPER;
        }

        return $html;
    }

    public function getPaginate($currentPage, $maxPerPage, $itemCount, $pathInfo)
    {
        if ($maxPerPage >= $itemCount) {
            return;
        }

        $list = range(1, $itemCount);
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

    public function getTabSelect($tabPath, $pathInfo)
    {
        return $tabPath === $pathInfo ? 'class="tab_select"' : "";
    }
}
