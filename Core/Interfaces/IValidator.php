<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 02.12.17
 * Time: 19:57
 */
namespace Interfaces;

/**
 * Interface IValidator
 * @package Core
 */
interface IValidator
{
    /**
     * @param $value
     * @return string
     */
    function isValid($value);
}