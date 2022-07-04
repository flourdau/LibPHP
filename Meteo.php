<?php
/*
* Météo API OPENWEATHERMAP
*/
namespace App\Lib;

setlocale(LC_ALL, "fr_FR");
date_default_timezone_set('Europe/Paris');
define("KEY_OPENWEATHERMAP", "c3413a33b8d69c8f9600b3a50085a316");


class Meteo
{
        private $tabApi       = [];

        public function __construct(\DateTime $usrDate, string $city)
        {
                $keyMeteo = KEY_OPENWEATHERMAP;

                $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&lang=fr&units=metric&appid=" . $keyMeteo;
                $contents = file_get_contents($url);

                $jsonMeteo = json_decode($contents, TRUE);
                
                $url = "http://api.openweathermap.org/data/2.5/air_pollution?lat=" . $jsonMeteo['coord']['lat'] . "&lon=" . $jsonMeteo['coord']['lat'] . "&appid=" . $keyMeteo;
                $contents2 = file_get_contents($url);
                $jsonAir = json_decode($contents2, TRUE);

                $this->tabApi = ["Meteo" => $jsonMeteo, "Air" => $jsonAir];
        }

        public function getMeteo () {
                return $this->tabApi;
        }

}