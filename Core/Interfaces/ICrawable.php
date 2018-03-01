<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 15:57
 */
namespace Interfaces;

/**
 * Interface ICrawable
 * @package Interfaces
 */
interface ICrawable
{
    /**
     * @param $url
     * @return mixed
     */
    function crawl($url);
}