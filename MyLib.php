<?php
/*
* MyLib Functions
*/
namespace App\Lib;

class MyLib
{ 
    public static function confirm($str = "Voulez-vous continuez?")
    {
        while (True) {
            $input = readline($str . " - (O)ui/(N)on - (Yes)/(N)o : ");
            if ($input === 'o' || $input === 'y') {
                return True;
            } elseif ($input === 'n') {
                return False;
            }
        }
    }

    public static function checkCrypt($post = null, $pass = null)
    {
        return password_verify(hash('whirlpool', $post), $pass);
    }

    public static function crypt($pass = null)
    {
        return password_hash(hash('whirlpool', $pass), PASSWORD_BCRYPT);
    }


    public static function strRandom($len = 16)
    {
        $str = str_repeat("QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789");
        return substr(str_shuffle($str, $len), 0, $len);
    }


    public static function strClean($str = null)
    {
        return preg_replace('#[^[:alnum:]]#u', '', $str);
    }


    public static function strfname($prefix = null, $name = null, $cnt = null, $ext = null)
    {
        return $prefix . $name . $cnt . $ext;
    }


    public static function strIsMail($mail = null)
    {
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
            return true;
        }
    }

    public static function strIsPhone($phone = null)
    {
        if (preg_match("#^0[1-68]([-. ]?\d{2}){4}$#", $phone)) {
            return true;
        }
    }

    public static function getTime()
    {
        static $timer = false, $start;

        if ($timer === false) {
            $start = array_sum(explode(' ', microtime()));
            $timer = true;
            return null;
        } else {
            $timer = false;
            $end = array_sum(explode(' ', microtime()));
            return round(($end - $start) * 1000, 3);
        }
    }

    public static function getIp()
    {
        $request = 'http://bot.whatismyipaddress.com/';
        $response  = file_get_contents($request);
        return "LAN : " . gethostbyname(gethostname()) . " WAN : " . $response;
    }

    public static function myMeteo()
    {
        $request = 'https://api.openweathermap.org/data/2.5/weather?q=Stains,
        fr&APPID=09f3b00b920d29d07bec348a0b380033&units=metric';
        $response  = file_get_contents($request);
        return (json_decode($response));
    }

    public static function loopTxt2ASCII(string $T = "HelloWorld", int $L = 4, int $H = 5) : ?string
    {
        $tmp = [];
        $alpha = " -!ABCDEFGHIJKLMNOPQRSTUVWXYZ?";
        $ROW = "\n";
        $tmp = str_split(trim($T));
        $handle = @fopen(__DIR__ . "/../../ascii.txt", "r");
        if ($handle) {
            while ($buffer = stream_get_line($handle, 256 + 1, "\n")) {
                foreach ($tmp as $c) {
                    $position = mb_stripos($alpha, $c);
                    if ($position === false) {
                        $position = 28;
                    }
                    $ROW .= mb_substr($buffer, ($position * $L), $L);
                }
                $ROW = $ROW . "\n";
            }
            if (!feof($handle)) {
                echo "Erreur: fopen() a échoué\n";
                die;
            }
            fclose($handle);
            return $ROW;
        }
        return null;
    }
}
