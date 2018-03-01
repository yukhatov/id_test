<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 17:12
 */
namespace Classes;

use Interfaces\IDataConverter;
use Interfaces\IDataMerger;
use Interfaces\IManager;

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
     * @var IDataMerger
     */
    private $dataMerger;

    /**
     * ReportManager constructor.
     * @param IDataConverter $dataConverter
     * @param IDataMerger $dataMerger
     * @throws Exception if config broken.
     */
    public function __construct(IDataConverter $dataConverter, IDataMerger $dataMerger)
    {
        $this->dataMerger = $dataMerger;
        $this->dataConverter = $dataConverter;
        $this->config = @parse_ini_file(__DIR__ . '/../../config.ini', true);
        
        if ( !$this->config ) {
            throw new \Exception("Config parsing eror!");
        }
    }

    /**
     * Builds new reports or adds data to existing ones
     * 
     * @param array $data
     * @return bool
     */
    public function report(array $data) : bool
    {
        $name = sprintf($this->config['report']['name'], date('d.m.Y'));
        $reportHTML = @file_get_contents($this->config['report']['dir'] . $name);

        /* If file does not exist or empty */
        if ($reportHTML === false || !strlen($reportHTML)) {
            return $this->buildAndWriteReport([0 => $data], $name);
        }

        /* If file has records than analize and insert */
        $reportArray = $this->dataConverter->convert($reportHTML);
        $reportArray = $this->dataMerger->merge($reportArray, $data);

        return $this->buildAndWriteReport($reportArray, $name);
    }

    /**
     * Builds html table from array and writes to file
     * 
     * @param array $reportArray
     * @param string $name
     * @return bool
     */
    private function buildAndWriteReport(array $reportArray, string $name) : bool
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

        if (!@file_put_contents($this->config['report']['dir'] . $name, $reportData)) {
            return false;
        }
        
        return true;
    }
}
