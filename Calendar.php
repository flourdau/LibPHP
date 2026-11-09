<?php
namespace App\Lib;

define("DICOS_PATH", file_exists(__DIR__ . "/../Collections/calendar.json")
    ? __DIR__ . "/../Collections/calendar.json"
    : __DIR__ . "/../../depotsGIT/Collections/calendar.json");

class Calendar
{
    private array $myTabCalendar = [];

    private static function formatFr(\DateTimeInterface $date, string $pattern): string
    {
        if (class_exists(\IntlDateFormatter::class)) {
            $fmt = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris', null, $pattern);
            $res = $fmt->format($date);
            if ($res !== false) {
                return ucwords($res);
            }
        }
        return $date->format('Y-m-d');
    }

    public function __construct(\DateTime $usrDate)
    {
        $nowDate     = new \DateTime();
        $tabCalendar = [];
        $usrYear     = (int) $usrDate->format("Y");
        $nowYear     = (int) $nowDate->format("Y");
        $calendar    = json_decode((string) file_get_contents(DICOS_PATH), true);

        $md    = $usrDate->format("m-d");
        $month = $usrDate->format("m");
        $day   = $usrDate->format("d");

        // Check Saisons
        if (isset($calendar['saisons']) && is_array($calendar['saisons'])) {
            foreach ($calendar['saisons'] as $season) {
                $matched = false;
                if (isset($season['dates']) && is_array($season['dates'])) {
                    foreach ($season['dates'] as $range) {
                        if (is_array($range) && count($range) === 2 && $md >= $range[0] && $md <= $range[1]) {
                            $matched = true;
                            break;
                        }
                    }
                }
                if (!$matched && isset($season['start'], $season['end']) && $md >= $season['start'] && $md <= $season['end']) {
                    $matched = true;
                }
                if ($matched) {
                    $label = $season['label'] ?? 'Inconnue';
                    $tabCalendar['Saison'] = [
                        $label => [$season['start'] ?? '12-21', $season['end'] ?? '03-20']
                    ];
                    break;
                }
            }
        }

        // Check Fetes
        $fete = $calendar['fetes'][$md] ?? null;
        $tabCalendar['Fetes'] = $fete ? (is_array($fete) ? $fete : [$fete]) : ["Aucune"];

        // Ephemeride (Prenoms & Saints)
        $dayInfo   = $calendar['ephemeride'][$month][$day] ?? [];
        $prenoms   = $dayInfo['prenom'] ?? [];
        $saints    = $dayInfo['saint'] ?? [];
        $dictons   = $calendar['dictons'] ?? [];
        $monthIdx  = (int) $usrDate->format("n") - 1;

        $tabCalendar = array_merge(
            $tabCalendar,
            [
                "Timestamp"      => $usrDate->getTimestamp(),
                "Atom"           => $usrDate->format(DATE_ATOM),
                "Hour"           => $usrDate->format('H:i:s'),
                "Day"            => [
                    "FirstName" => $prenoms,
                    "Saint"     => $saints,
                    "dayMonth"  => $day,
                    "dayYear"   => $usrDate->format("z"),
                    "nameFr"    => self::formatFr($usrDate, 'EEEE'),
                    "Name"      => $usrDate->format("l"),
                ],
                "Month"          => [
                    "monthYear" => $month,
                    "nameFr"    => self::formatFr($usrDate, 'MMMM'),
                    "Name"      => $usrDate->format("F"),
                ],
                "Year"           => $usrDate->format('Y'),
                "Bissextile"     => $usrDate->format("L"),
                "Age"            => ($nowYear - $usrYear),
                "Semaine"        => $usrDate->format("W"),
                "Fuseau Horaire" => $usrDate->format("e"),
                "Dicton"         => $dictons[$monthIdx] ?? null,
            ]
        );

        $this->myTabCalendar = $tabCalendar;
    }

    public function getCalendar(): array
    {
        return $this->myTabCalendar;
    }
}
