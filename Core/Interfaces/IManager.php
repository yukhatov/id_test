<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 19:57
 */
namespace Interfaces;

/**
 * Interface IManager
 * @package Interfaces
 */
interface IManager
{
    /**
     * @param array $data
     * @return bool
     */
    function report(array $data) : bool;
}