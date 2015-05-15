<?php
namespace Blog\Manage;

use WebStream\Core\CoreService;
use WebStream\Exception\Extend\ResourceNotFoundException;

/**
 * ManageService
 */
class ManageService extends CoreService
{
    public function setViewModel(array $params)
    {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function entryCount(array $params)
    {
        $result = $this->Manage->entryCount($params)->toArray();

        return intval($result[0]['count']);
    }

    public function entryById($entryId)
    {
        $entryMap = [];
        foreach ($this->Manage->entryById(['entry_id' => $entryId]) as $entry) {
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

        if (count($entryMap) === 0) {
            throw new ResourceNotFoundException("Entry not found.");
        }

        $this->entry = $entryMap;
        $this->entryId = $entryId;
    }
}
