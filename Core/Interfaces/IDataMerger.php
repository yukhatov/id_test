<?php
/**
 * Created by PhpStorm.
 * User: littleprince
 * Date: 01.03.18
 * Time: 14:26
 */
namespace Interfaces;

/**
 * Interface IDataMerger
 * @package Interfaces
 */
interface IDataMerger
{
    /**
     * @param array $target
     * @param array $data
     * @return array
     */
    function merge(array $target, array $data) : array;
}