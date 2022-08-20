<?php
namespace App\Lib;

class Meteo
{
    private $tabApi = [];

    public function __construct(string $city, string $keyMeteo)
    {
        $url        = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&lang=fr&units=metric&appid=" . $keyMeteo;
        $contents   = @file_get_contents($url);
        /* Si echec on relance avec Paris comme emplacement... */
        if (empty($contents)) {
            $url    = "http://api.openweathermap.org/data/2.5/weather?q=Paris&lang=fr&units=metric&appid=" . $keyMeteo;
            $contents = @file_get_contents($url);
        }
        $jsonMeteo  = json_decode($contents, TRUE);
        $url        = "http://api.openweathermap.org/data/2.5/air_pollution?lat=" . $jsonMeteo['coord']['lat'] . "&lon=" . $jsonMeteo['coord']['lon'] . "&appid=" . $keyMeteo;
        $contents2  = @file_get_contents($url);
        // Debug::dd($this->tabApi);
        $this->tabApi = array_merge($this->tabApi, ["Meteo" => $jsonMeteo], ["Air"  => json_decode($contents2, TRUE)]);
    }

    public function getMeteo ()
    {
        return $this->tabApi;
    }
}