<?php
namespace Blog\Entry;

use WebStream\Core\CoreHelper;
use WebStream\Module\Utility;
use WebStream\Database\Result;

class EntryApiHelper extends CoreHelper
{
    use Utility;

    public function getMonthlyArchive(array $archiveMap)
    {
        $html = "";

        foreach ($archiveMap as $year => $monthMap) {
            $html .= <<< HELPER
<h3 class="archive_year">${year}年</h3>
<ul>
HELPER;
            foreach ($monthMap as $month => $count) {
                $html .= <<< HELPER
<li><a href="/diarysys/month/${year}${month}">${month}月(${count})</a></li>
HELPER;
            }
            $html .= <<< HELPER
</ul>
HELPER;
        }

        return $html;
    }

    public function getTagList(Result $tagList)
    {
        $html = "<ul>";

        foreach ($tagList as $tag) {
            $name = $tag["name"];
            $count = $tag["count"];
            $url = "/diarysys/tag/" . safetyOut($name);

            $countNum = ceil($count);
            $className = "tagcloud" . ($countNum > 10 ? "10" : strval($countNum));

            $html .= <<< HELPER
<li class="${className}"><a href="${url}">${name}</a></li>
<sup>${count}</sup>
HELPER;
        }

        $html .= "</ul>";

        return $html;
    }
}
