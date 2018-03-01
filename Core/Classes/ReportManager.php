<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 17:12
 */
namespace Classes;

use Interfaces\IDataConverter;
use Interfaces\IManager;
use DOMDocument;

/**
 * Class ReportManager
 * @package Classes
 */
class ReportManager implements IManager
{
    /* Report keys 
     *
     * @var string
     */
    public static $key_url = 'url';
    /**
     * @var string
     */
    public static $key_count_of_tags = 'count_of_tags';
    /**
     * @var string
     */
    public static $key_duration = 'duration';

    /**
     * @var array
     */
    private $config;

    /**
     * @var IDataConverter
     */
    private $dataConverter;

    /**
     * ReportManager constructor.
     * @param IDataConverter $converter
     * @throws Exception if config broken.
     */
    public function __construct(IDataConverter $dataConverter)
    {
        $this->dataConverter = $dataConverter;
        $this->config = @parse_ini_file(__DIR__ . '/../../config.ini', true);
        
        if ( !$this->config ) {
            throw new \Exception("Config parsing eror!");
        }
    }

    /**
     * Builds new reports or adds data to existing ones
     *
     * @param $data
     */
    public function report($data)
    {
        $name = sprintf($this->config['report']['name'], date('d.m.Y'));
        $reportHTML = @file_get_contents($this->config['report']['dir'] . $name);

        /* If file does not exist or empty */
        if ($reportHTML === false || !strlen($reportHTML)) {
            $this->buildAndWriteReport([0 => $data], $name);

            return;
        }

        /* If file has records than analize and insert */
        $reportArray = $this->dataConverter->convert($reportHTML);
        $reportArray = $this->insertDataIntoReport($reportArray, $data);

        return $this->buildAndWriteReport($reportArray, $name);
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
            if ($report[self::$key_count_of_tags] >= $reportData[self::$key_count_of_tags]) {
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
        $reportData = '<table>
          <tr>
            <th>url</th>
            <th>count_of_tags</th> 
            <th>duration</th>
          </tr>';

        foreach ($reportArray as $report) {
            $reportData .= '<tr>
                <td>' . $report[self::$key_url] .'</td>
                <td>' . $report[self::$key_count_of_tags] . '</td>
                <td>' . $report[self::$key_duration] . '</td>
              </tr>';
        }

        $reportData .= '</table>';

        return file_put_contents($this->config['report']['dir'] . $name, $reportData);
    }
}
