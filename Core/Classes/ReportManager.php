<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 17:12
 */
namespace Classes;

use Interfaces\IManager;
use DOMDocument;

/**
 * Class ReportManager
 * @package Classes
 */
class ReportManager implements IManager
{
    /**
     * Sub directory for reports
     */
    const REPORT_DIR = 'Report/';

    /**
     * Builds new reports or adds data to existing ones
     *
     * @param $data
     */
    public function report($data)
    {
        $name = "report_" . date('d.m.Y') . ".html";
        $reportHTML = @file_get_contents(self::REPORT_DIR . $name);

        /* If file does not exist or empty */
        if ($reportHTML === false || !strlen($reportHTML)) {
            $this->buildAndWriteReport([0 => $data], $name);

            return;
        }

        /* If file has records than analize and insert */
        $reportArray = $this->getReportArrayFromHtml($reportHTML);
        $reportArray = $this->insertDataIntoReport($reportArray, $data);

        $this->buildAndWriteReport($reportArray, $name);
    }

    /**
     * Analyzes existing report and insets data into ordered position
     *
     * @param $reportArray
     * @return array
     */
    private function insertDataIntoReport($reportArray, $reportData)
    {
        $isInserted = false;

        foreach ($reportArray as $keyReport => $report) {
            if ($report['count_of_tags'] >= $reportData['count_of_tags']) {
                array_splice(
                    $reportArray,
                    $keyReport,
                    0,
                    [$keyReport + 1 => $reportData]
                );

                $isInserted = true;

                break;
            }
        }

        if (!$isInserted) {
            $reportArray[] = $reportData;
        }

        return $reportArray;
    }

    /**
     * Builds html table from array and writes to file
     *
     * @param $reportArray
     * @param $name
     */
    private function buildAndWriteReport($reportArray, $name)
    {
        $reportData = "<table>
          <tr>
            <th>url</th>
            <th>count_of_tags</th> 
            <th>duration</th>
          </tr>";

        foreach ($reportArray as $report) {
            $reportData .= "<tr>
                <td>" . $report['url'] ."</td>
                <td>" . $report['count_of_tags'] . "</td>
                <td>" . $report['duration'] . "</td>
              </tr>";
        }

        $reportData .= "</table>";

        file_put_contents(self::REPORT_DIR . $name, $reportData);
    }

    /**
     * Converts html table to array
     *
     * @param $reportHTML
     * @return mixed
     */
    private function getReportArrayFromHtml($reportHTML)
    {
        $DOM = new DOMDocument();
        $DOM->loadHTML($reportHTML);

        $Header = $DOM->getElementsByTagName('th');
        $Detail = $DOM->getElementsByTagName('td');

        foreach($Header as $NodeHeader) {
            $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
        }

        $i = 0;
        $j = 0;

        foreach ($Detail as $sNodeDetail) {
            $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
            $i = $i + 1;
            $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
        }

        for ($i = 0; $i < count($aDataTableDetailHTML); $i++) {
            for ($j = 0; $j < count($aDataTableHeaderHTML); $j++) {
                $aTempData[$i][$aDataTableHeaderHTML[$j]] = $aDataTableDetailHTML[$i][$j];
            }
        }

        $aDataTableDetailHTML = $aTempData; unset($aTempData);

        return $aDataTableDetailHTML;
    }
}
