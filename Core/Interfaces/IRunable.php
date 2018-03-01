<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 15:57
 */
namespace Interfaces;

/**
 * Interface IRunable
 * @package Interfaces
 */
interface IRunable
{
    /**
     * @param $url
     * @return mixed
     */
    function run($url);
}