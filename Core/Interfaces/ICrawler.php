<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 19:57
 */
namespace Interfaces;

/**
 * Interface ICrawler
 * @package Interfaces
 */
interface ICrawler
{
    /**
     * @param $url
     * @return mixed
     */
    public function getResource($url);

    /**
     * @param $resource
     * @param $tag
     * @return mixed
     */
    public function countTags($resource, $tag);
}