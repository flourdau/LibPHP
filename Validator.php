<?php
namespace App\Lib;

// use Symfony\Component\Console\Exception\InvalidArgumentException;
// use Symfony\Component\String\u;

class Validator
{
    public function checkStrDate(string $strDate)
    {
        if (preg_match("/^(?'year'\d{4})-(?'month'\d{2})-(?'day'\d{2})$/", $strDate, $tmp)) {
            if (checkdate($tmp['month'], $tmp['day'], $tmp['year'])) {
                return (new \DateTime($tmp['day'] . "-" . $tmp['month'] . "-" . $tmp['year'], new \DateTimeZone("Europe/Paris")))->format('d-m-Y');
            }
        }
        return (new \DateTime("now", new \DateTimeZone("Europe/Paris")))->format('d-m-Y');
    }
        
    public function checkStrTime(string $strTime)
    {
        if (preg_match("/^(?'hour'\d{2}):(?'minute'\d{2}):(?'seconde'\d{2})$/", $strTime, $tmp)) {
            $hour       = settype($hour, 'int');
            $minute     = settype($minute, 'int');
            $seconde    = settype($seconde, 'int');

            if ($hour >= 0 && $hour <= 24 && $minute >= 0 && $minute <= 60 && $seconde >= 0 && $seconde <= 60) {
                return (new \DateTime($strTime, new \DateTimeZone("Europe/Paris")))->format('H:i:s');
            }
        }
        return (new \DateTime("now", new \DateTimeZone("Europe/Paris")))->format('H:i:s');
    }

    public function checkMyCity($strCity): string
    {
        $strCity    = strtolower(trim(htmlspecialchars($strCity)));
        $len        = strlen($strCity);

        if (empty($strCity) || $len <= 3 || $len >= 20 || !preg_match("/^[a-z0-9%_\séèêàïîçôû-]+$/", $strCity)) {
            return ('Paris');
        }
        return $strCity;
    }

    public function checkStrUsername(string $username): string
    {
        if (empty($username)) {
            return ('The username can not be empty.');
        }
        $len = strlen($username);
        if ($len < 0 || $len > 30) {
            return ('The len username can not be good.');
        }
        if (1 !== preg_match('/^[a-zA-Z0-9_ ]+$/', $username)) {
            return ('The username must contain only lowercase latin characters and underscores.');
        }
        return $username;
    }

    public function cleanArrayMeteo(array $meteo): array
    {
        $newMeteo                       = [
                                            'Meteo' => [],
                                            'Air'   => []
                                        ];

        $newMeteo['Meteo']['coord']     = $meteo['Meteo']['coord'];

        $newMeteo['Meteo']['weather']   = array_merge(
                                                    $meteo['Meteo']['weather'][0],
                                                    $meteo['Meteo']['main']);

        $newMeteo['Meteo']['wind']      = $meteo['Meteo']['wind'];

        $newMeteo['Meteo']['sys']       = array_merge(
                                                $meteo['Meteo']['sys'],
                                                ['name' => $meteo['Meteo']['name']]);

        if (!isset($newMeteo['Air']['list'])) {
            $newMeteo['Air']['list']        = array_merge(
                                                    $meteo['Air']['list'][0]['main'],
                                                    $meteo['Air']['list'][0]['components']);
        }
        return $newMeteo;
    }
/*        
    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (u($plainPassword)->trim()->length() < 6) {
            throw new InvalidArgumentException('The password must be at least 6 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (null === u($email)->indexOf('@')) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
                throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $fullName;
    }*/
}