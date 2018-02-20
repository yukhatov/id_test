<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 16:50
 */
namespace Classes;

use Interfaces\IValidator;

/**
 * Class Validator
 * @package Core
 */
class Validator implements IValidator
{
    /**
     * Validates url if it works
     *
     * @param $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!@file_get_contents($value)) {
            return false;
        }

       return true;
    }
}