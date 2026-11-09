<?php
namespace App\Lib;

class Meteo
{
    private array $tabApi = [];

    public function __construct(string $city, string $keyMeteo)
    {
        if (empty($keyMeteo) || $keyMeteo === 'dummy_key') {
            $this->tabApi = ['Meteo' => null, 'Air' => null];
            return;
        }

        $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true]]);
        $encodedCity = rawurlencode($city);
        $encodedKey = rawurlencode($keyMeteo);

        $url = "https://api.openweathermap.org/data/2.5/weather?q={$encodedCity}&lang=fr&units=metric&appid={$encodedKey}";
        $contents = @file_get_contents($url, false, $ctx);

        /* Si échec on relance avec Paris comme emplacement... */
        if (empty($contents)) {
            $url = "https://api.openweathermap.org/data/2.5/weather?q=Paris&lang=fr&units=metric&appid={$encodedKey}";
            $contents = @file_get_contents($url, false, $ctx);
        }

        $jsonMeteo = is_string($contents) ? json_decode($contents, true) : null;
        $jsonAir = null;

        if (is_array($jsonMeteo) && isset($jsonMeteo['coord']['lat'], $jsonMeteo['coord']['lon'])) {
            $lat = rawurlencode((string) $jsonMeteo['coord']['lat']);
            $lon = rawurlencode((string) $jsonMeteo['coord']['lon']);
            $urlAir = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$encodedKey}";
            $contentsAir = @file_get_contents($urlAir, false, $ctx);
            $jsonAir = is_string($contentsAir) ? json_decode($contentsAir, true) : null;
        }

        $this->tabApi = [
            "Meteo" => $jsonMeteo,
            "Air"   => $jsonAir,
        ];
    }

    public function getMeteo(): array
    {
        return $this->tabApi;
    }
}
