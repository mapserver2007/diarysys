<?php
namespace Blog\Entry;

use WebStream\Core\CoreService;

class EntryService extends CoreService
{

    private $entryList;

    public function getEntryList()
    {
        return $this->entryList;
    }

    public function entryList($num, $page = 1)
    {
        $entryMap = [];
        foreach ($this->Entry->entryList($num, $page) as $entry) {
            $entryId = $entry["id"];
            if (!array_key_exists($entryId, $entryMap)) {
                $entryMap[$entryId] = [
                    "id" => $entryId,
                    "title" => $entry["title"],
                    "description" => $entry["description"],
                    "createdAt" => $entry["created_at"],
                    "categoryMapList" => [],
                    "tagMapList" => []
                ];
            }

            $tagId = $entry["tag_id"];
            $tagName = $entry["tag_name"];
            if ($tagId !== null && $tagName !== null) {
                if (!array_key_exists($tagId, $entryMap[$entryId]["tagMapList"])) {
                    $entryMap[$entryId]["tagMapList"][$tagId] = $tagName;
                }
            }

            $categoryId = $entry["category_id"];
            $categoryName = $entry["category_name"];
            if ($categoryId !== null && $categoryName !== null) {
                if (!array_key_exists($categoryId, $entryMap[$entryId]["categoryMapList"])) {
                    $entryMap[$entryId]["categoryMapList"][$categoryId] = $categoryName;
                }
            }
        }

        $this->entryList = $entryMap;
    }
}
