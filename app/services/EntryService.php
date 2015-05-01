<?php
namespace Blog\Entry;

use WebStream\Core\CoreService;

/**
 * EntryService
 */
class EntryService extends CoreService
{
    private $pathInfo;

    private $entryList;

    private $entryCount;

    private $currentPage;

    private $maxPerPage;

    public function setPathInfo($pathInfo)
    {
        $this->pathInfo = $pathInfo;
    }

    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    public function getEntryList()
    {
        return $this->entryList;
    }

    public function getEntryCount()
    {
        return $this->entryCount;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    public function entryList(array $params)
    {
        $this->currentPage = $params['page'];
        $this->maxPerPage = $params['num'];
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

    public function entryCount(array $params)
    {
        $result = $this->Entry->entryCount($params)->toArray();
        $this->entryCount = intval($result[0]['count']);
    }
}
