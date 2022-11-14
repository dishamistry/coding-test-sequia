<?php

namespace application\core;

class Helper
{
    protected static $registry = [];

    public static function bindConfig($key, $value)
    {
        static::$registry[$key] = $value;
    }

    public static function getConfig($key)
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new \Exception("NO {$key} is  bound in container");
        }

        return static::$registry[$key];
    }

    public static function uri()
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function csvToArray($filename)
    {
            $data = array();
            if (!file_exists($filename))
                return $data;

            $f = fopen($filename, 'r');
            if ($f === false)
                die('Cannot open the file ' . $filename);

            while (($row = fgetcsv($f)) !== false) {
                array_push($data, $row);
            }
            fclose($f);
            return $data;
    }
}
