<?php
namespace Blog\Manage;

use WebStream\Core\CoreService;

class ManageApiService extends CoreService
{
    public function loadImage($page, $limit)
    {
        $dir = STREAM_ROOT . "/app/views/_public/img/upload/thumbnail";
        $list = [];
        $hash = [];
        $offset = $limit * ($page - 1);
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if (preg_match("/(?:(?:jpe?|pn)g|(?:tif|gi)f)$/", $entry)) {
                    $filepath = $dir . "/" . $entry;
                    $hash[filemtime($filepath)] = $entry;
                }
            }
            closedir($handle);
        }
        // タイムスタンプの降順にソート
        arsort($hash);
        // ファイル名のリストを作成
        $i = 0;
        $j = 0;
        foreach ($hash as $filename) {
            if ($offset > $i++) continue;
            if ($limit > $j++) {
                $list[] = $filename;
            } else {
                break;
            }
        }

        $this->currentPage = $page;
        $this->imageCount = count($hash);
        $this->maxPerPage = $limit;
        $this->imageList = $list;
    }
}
