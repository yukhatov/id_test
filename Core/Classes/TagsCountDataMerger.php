<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 16:50
 */
namespace Classes;

use Interfaces\IDataMerger;

/**
 * Class TagsCountDataMerger
 * @package Classes
 */
class TagsCountDataMerger implements IDataMerger
{
    /**
     * Analyzes existing report and insets data into ordered position
     * 
     * @param array $target
     * @param array $data
     * @return array
     */
    public function merge(array $target, array $data) : array
    {
        $isInserted = false;

        foreach ($target as $keyReport => $report) {
            if ($report[ReportManager::$key_count_of_tags] >= $data[ReportManager::$key_count_of_tags]) {
                array_splice(
                    $target,
                    $keyReport,
                    0,
                    [$keyReport + 1 => $data]
                );

                $isInserted = true;

                break;
            }
        }

        if (!$isInserted) {
            $target[] = $data;
        }

        return $target;
    }
}