<?php
namespace App\Lib;

use App\Lib\Debug;

define("DICOS_PATH", "../depotsGIT/Collections/calendar.json");

class Calendar
{
    private $myTabCalendar = [];

    public function __construct(\DateTime $usrDate)
    {
        $nowDate        = new \DateTime();
        $tabCalendar    = [];
        $cntDaySaint    = intval($usrDate->format("z"));

        if (!$usrDate->format("L") && $cntDaySaint > 60) {
            $cntDaySaint++;
        }
        $usrYear = $usrDate->format("Y");
        settype($usrYear, "int");
        $nowYear = $nowDate->format("Y");
        settype($nowYear, "int");
        $calendar = json_decode(file_get_contents(DICOS_PATH), false);

        //      Check Saisons
        foreach ($calendar->Saisons as $name => $tab) {
                $tmp1 = \DateTime::createFromFormat('m-d', $tab[0], new \DateTimeZone("Europe/Paris"));
                $tmp2 = \DateTime::createFromFormat('m-d', $tab[1], new \DateTimeZone("Europe/Paris"));

                if ($usrDate->format("m-d") >= $tmp1->format("m-d") && $usrDate->format('m-d') <= $tmp2->format("m-d")) {
                    if ($name === "Hiver2") {
                        $name = "Hiver";
                    }
                    if ($name === "Hiver") {
                        $calendar->Saisons->$name[0] = "12-21";
                        $calendar->Saisons->$name[1] = "03-20";
                    }
                    $tabCalendar = array_merge($tabCalendar, ['Saison' => [$name => $calendar->Saisons->$name]]);
                }
        }

        //      Check Fetes
        $tabCalendar['Fetes'] = ["Aucune"];
        foreach ($calendar->Fetes as $date => $tab) {
                if ($date == $usrDate->format('m-d')) {
                    $tabCalendar = array_merge($tabCalendar, ['Fetes' => $tab]);
                }
        }

        $month  = $usrDate->format('m');
        $day    = $usrDate->format('d');

        $tabCalendar = array_merge($tabCalendar, 
        [
            "Timestamp"         => $usrDate->getTimestamp(),
            "Atom"             => $usrDate->format(DATE_ATOM),
            "Hour"              => $usrDate->format('H:i:s'),
            "Day"               => [
                                        "FirstName" => $calendar->Prenoms->$month->$day,
                                        "Saint"     => $calendar->Saints[$cntDaySaint],
                                        "dayMonth"  => $day,
                                        "dayYear"   => $usrDate->format("z"),
                                        "nameFr"    => ucwords(strftime("%A",$usrDate->getTimestamp())),
                                        "Name"      => $usrDate->format("l")
                                    ],
            "Month"             => [
                                        "monthYear" => $month,
                                        "nameFr"    => ucwords(strftime("%B", $usrDate->getTimestamp())),
                                        "Name"      => $usrDate->format("F")
                                    ],
            "Year"              => $usrDate->format('Y'),
            "Bissextile" 		=> $usrDate->format("L"),
            "Age"               => ($nowYear - $usrYear),
            "Semaine"           => $usrDate->format("W"),
            "Fuseau Horaire"    => $usrDate->format("e"),
            "Dicton"        	=> $calendar->Dictons[$usrDate->format("n") - 1]
        ]);

        // if ($usrDate->getTimestamp() > 0) {
        //         array_push($tabCalendar, ["Pâques" => date("d-m-Y", easter_date($usrDate->format("Y")))]);
        // }

// Debug::dd($tabCalendar);
        $this->myTabCalendar = $tabCalendar;
    }

    public function getCalendar()
    {
        return $this->myTabCalendar;
    }
}