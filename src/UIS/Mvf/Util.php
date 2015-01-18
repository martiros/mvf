<?php

namespace UIS\Mvf;

class Util
{
    /**
     * @param mixed $value
     * @return bool
     */
    public static function isInt($value)
    {
        if (is_int($value)) {
            return true;
        }

        if (preg_match("/^-?[1-9][0-9]*$/D", $value)) {
            return true;
        }
        if ($value === '0') {
            return true;
        }
        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isDigit($value)
    {
        if (preg_match("/^[[:digit:]]+$/", $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param mixed $email
     * @return bool
     */
    public static function isEmail($email)
    {
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
        return ( boolean )$isValid;
    }

    /**
     * @param mixed $string
     * @return bool
     */
    public static function isString($string)
    {
        return is_string($string);
    }

    /**
     * Check is valid date in format Y-m-d
     * @param string $date
     * @return bool
     */
    public static function isDate($date)
    {
        //match the format of the date
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //check weather the date is valid of not
            if (checkdate($parts[2], $parts[3], $parts[1])) {
                return true;
            }
        }
        return false;
    }

    public static function isUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return true;
        }
        return false;
    }

    /**
     * @param string $str
     * @param string $encoding
     * @return int
     */
    public static function strLen($str, $encoding = 'UTF-8')
    {
        if ($encoding === null) {
            return mb_strlen($str, $encoding);
        }
        return mb_strlen($str, $encoding);
    }
}
