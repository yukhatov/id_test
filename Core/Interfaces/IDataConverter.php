<?php
/**
 * Created by PhpStorm.
 * User: littleprince
 * Date: 01.03.18
 * Time: 14:26
 */
namespace Interfaces;

/**
 * Interface IDataConverter
 * @package Interfaces
 */
interface IDataConverter
{
    /**
     * @param $data
     * @return mixed
     */
    function convert($data) : array;
}