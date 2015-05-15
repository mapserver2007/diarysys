<?php
namespace Blog\Manage;

use WebStream\Core\CoreHelper;
use WebStream\Module\Utility;
use WebStream\Database\Result;

class ManageApiHelper extends CoreHelper
{
    use Utility;

    public function getTagList(Result $tagList)
    {
        $html = "<ul>";

        foreach ($tagList as $tag) {
            $id = $tag["id"];
            $name = $tag["name"];
            $count = $tag["count"];
            $url = "/diarysys/tag/" . safetyOut($name);

            $countNum = ceil($count);
            $className = "tagcloud" . ($countNum > 10 ? "10" : strval($countNum));

            $html .= <<< HELPER
<li>
<label><input type="checkbox" id="${id}" value="${id}" data-name="${name}"/>${name}</label>
</li>
HELPER;
        }

        $html .= "</ul>";

        return $html;
    }

    public function getCategory(Result $categoryList)
    {
        $html = "<ul>";
        $i = 0;
        foreach ($categoryList as $category) {
            $id = $category["id"];
            $name = $category["name"];

            $checked = "";
            if ($i++ == 0) {
                $checked = "checked=\"checked\"";
            }

            $html .= <<< HELPER
<li>
<label><input type="radio" id="${id}" value="${id}" data-name="${name}" name="category" ${checked}/>${name}</label>
</li>
HELPER;
        }

        $html .= "</ul>";

        return $html;
    }

    public function getImageList(array $imageList)
    {
        $html = "";
        $thumbHtml = <<< HTML
    <div class="thumb">
        <img src="/diarysys/img/upload/thumbnail/%s" data-name="%s" onclick="Blog.PC.Manage.selectImage(this);"/>
    </div>
HTML;

        foreach ($imageList as $image) {
            $html .= sprintf($thumbHtml, $image, $image);
        }

        return $html;
    }
}
