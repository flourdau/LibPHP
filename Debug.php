<?php
namespace App\Lib;

class Debug
{
    /*
    * Debug::printDebug(new Debug);
    * die;
    */
    private $tab = [];

    public function __construct()
    {
        $this->maxDebug();
        $this->getTab();
    }

    public function getTab()
    {
        $tab = $this->tab;
        return $tab;
    }

    private function initPHP()
    {
        $inipath = php_ini_loaded_file();

        if ($inipath) {
            return "Loaded php.ini = $inipath";
        }
        else {
            return 'A php.ini file is not loaded';
        }
    }

    private function maxDebug()
    {
        if (!isset($_SESSION)) {
            $_SESSION = [0 => 'No Session Party!'];
        }
        $this->tab = [
                        'init'          => $this->initPHP(),
                        'dir'           => __DIR__,
                        'file'          => __FILE__,
                        'namespace'     => __NAMESPACE__,
                        'class'         => __CLASS__,
                        'trait'         => __TRAIT__,
                        'method'        => __METHOD__,
                        'function_name' => __FUNCTION__,
                        'line'          => __LINE__,
                        'session'       => $_SESSION,
                        'env'           => $_ENV,
                        'post'          => $_POST,
                        'get'           => $_GET,
                        'files'         => $_FILES,
                        'IntMax'        => PHP_INT_MAX,
                        'cookie'        => $_COOKIE,
                        'classes'       => get_declared_classes(),
                        'server'        => $_SERVER
                    ];
    }

    public static function debug($value)
    {
        return Html::surround("pre", false, print_r($value, true));
    }

    public static function d($value)
    {
        echo Html::surround("pre", false, print_r($value, true));
    }

    public static function dd($value)
    {
        echo Html::surround("pre", false, print_r($value, true));
        die;
    }
}