<?php
namespace Blog\Entry;

use WebStream\Core\CoreHelper;
use WebStream\Module\Utility;

class EntryHelper extends CoreHelper
{
    use Utility;

    public function getEntryBox(array $entryList)
    {
        $html = "";

        foreach ($entryList as $entry) {
            $title = safetyOut($entry['title']);

            $html .= <<< HELPER
<div class="entry_box">
    <div class="entry_image">
        <a href="/"><img src="http://images.gunosy.com/4/29/5e5b5e90b1832c66130743eb3c2b979c_large.jpg"/></a>
    </div>
    <h2>$title</h2>
    <div class="description">ああああああああああああああああああああああああああああああああああああああ</div>
</div>
HELPER;
        }

        return $html;
    }
}
