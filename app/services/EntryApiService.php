<?php
namespace Blog\Entry;

use WebStream\Core\CoreService;

class EntryApiService extends CoreService
{
    private $archiveMap;

    public function getArchiveMap()
    {
        return $this->archiveMap;
    }

    public function entryMonthlyArchive()
    {
        $archiveMap = [];
        foreach ($this->EntryApi->entryMonthlyArchive() as $archive) {
            $dateList = explode('-', $archive['month']);
            $year = $dateList[0];
            $month = $dateList[1];

            if (!array_key_exists($year, $archiveMap)) {
                $archiveMap[$year] = [];
            }

            $archiveMap[$year][$month] = $archive['count'];
        }

        $this->archiveMap = $archiveMap;
    }
}
