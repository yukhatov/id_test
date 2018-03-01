<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 16:50
 */
namespace Classes;

use Interfaces\IDataConverter;
use DOMDocument;

/**
 * Class HtmlTableToArrayConverter
 * @package Core
 */
class HtmlTableToArrayConverter implements IDataConverter
{
    /*
     * Converts html table to array
     * 
     * @param $url
     * @return array
     */
    public function convert($table) : array
    {
        $DOM = new DOMDocument();
        $DOM->loadHTML($table);

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

        $aDataTableDetailHTML = $aTempData; 

        return $aDataTableDetailHTML;
    }
}