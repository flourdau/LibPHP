<?php
namespace App\Lib;

class Errors
{
    public static $setupErrors  = [
                                    "0" => "Invalid ",
                                    "1" => "Config already deployed ",
                                    "2" => "File missing : ",
                                    "3" => "Creation Log ",
                                    "4" => "No Framework "
                                ];

    public static $errors       = ["0" => "Co.db is DOWN!"];

    public static function setupError($nb_error, $error = null)
    {
        die ("Setup Error : " . self::$setupErrors[$nb_error].$error . "!<br>");
    }

    public static function error($nb_error, $error = null)
    {
        return "Error : " . self::$errors[$nb_error].$error . "!<br>";
    }
}