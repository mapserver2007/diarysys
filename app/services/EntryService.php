<?php
namespace Blog\Entry;

use WebStream\Core\CoreService;

/**
 * EntryService
 */
class EntryService extends CoreService
{
    public function entryList(array $params)
    {
        $this->currentPage = $params['page'];
        $this->maxPerPage = $params['num'];

        if (array_key_exists('query', $params)) {
            $this->query = $params['query'];
        }

        $entryMap = [];

        foreach ($this->Entry->entryList($params) as $entry) {
            $entryId = $entry["id"];
            if (!array_key_exists($entryId, $entryMap)) {
                $entryMap[$entryId] = [
                    "id" => $entryId,
                    "title" => $entry["title"],
                    "description" => $entry["description"],
                    "createdAt" => $entry["created_at"],
                    "categoryMapList" => [],
                    "tagMapList" => [],
                    "categoryName" => $entry["category_name"]
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

    public function entryCount(array $params)
    {
        $result = $this->Entry->entryCount($params)->toArray();
        $this->entryCount = intval($result[0]['count']);
    }
}
