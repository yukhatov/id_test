<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 17:12
 */
namespace Classes;

use Interfaces\ICrawler;

/**
 * Class Crawler
 * @package Classes
 */
class Crawler implements ICrawler
{
    /**
     * @param $url
     * @return bool|string
     */
    public function getResource($url)
    {
        return file_get_contents($url);
    }

    /**
     * @param $resource
     * @param $tag
     * @return int
     */
    public function countTags($resource, $tag)
    {
        return substr_count($resource, $tag);
    }
}
