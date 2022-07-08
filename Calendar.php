<?php
/*
* Calendar Functions
*/
namespace App\Lib;


define("DICOS_PATH", "../depotsGIT/dicosJSON/calendar.json");
use App\Lib\Debug;

class Calendar
{
        private $myTabCalendar = [];

        public function __construct(\DateTime $usrDate)
        {
                setlocale(LC_ALL, "fr_FR");
                date_default_timezone_set('Europe/Paris');
                setlocale(LC_TIME, 'fr_FR.utf8','fra');
        
                $nowDate = new \DateTime();
                $tabCalendar = [];

                $cntDaySaint = intval($usrDate->format("z"));

                if (!$usrDate->format("L") && $cntDaySaint > 60) {
                        $cntDaySaint++;
                }

                $usrYear = $usrDate->format("Y");
                settype($usrYear, "int");
                $nowYear = $nowDate->format("Y");
                settype($nowYear, "int");
                $calendar = json_decode(file_get_contents(DICOS_PATH), false);
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
                                array_push($tabCalendar, [$name => $calendar->Saisons->$name]);
                        }
                }
                
                $month  = $usrDate->format('m');
                $day    = $usrDate->format('d');
                array_push($tabCalendar, [
                                                "Name"		        =>	$calendar->Prenoms->$month->$day,
                                                "Timestamp"             =>	$usrDate->getTimestamp(),
                                                "Hour"                  =>      $usrDate->format('H:i:s'),
                                                "Day" 			=>	[
                                                                                        "Saint"		=>	$calendar->Saints[$cntDaySaint],
                                                                                        "dayMonth"      =>      $day,
                                                                                        "dayYear"    	=>	$usrDate->format("z"),
                                                                                        "nameFr"        =>      ucwords(strftime("%A",$usrDate->getTimestamp())),
                                                                                        "Name"  	=>	$usrDate->format("l")
                                                                                ],
                                                "Month"                 =>      [

                                                                                        "monthYear"     =>      $month,
                                                                                        "nameFr"        =>      ucwords(strftime("%B", $usrDate->getTimestamp())),
                                                                                        "Name"          =>      $usrDate->format("F")
                                                                                ],
                                                "Year"                  =>      $usrDate->format('Y'),
                                                "Bissextile" 		=>	$usrDate->format("L"),
                                                "Age"			=>	($nowYear - $usrYear),
                                                "Semaine"		=>	$usrDate->format("W"),
                                                "Fuseau Horaire"	=>	$usrDate->format("e"),
                                                "Dicton"        	=>	$calendar->Dictons[$usrDate->format("n") - 1]
                                        ]);
                // if ($usrDate->getTimestamp() > 0) {
                //         array_push($tabCalendar, ["Pâques" => date("d-m-Y", easter_date($usrDate->format("Y")))]);
                // }
                $this->myTabCalendar = $tabCalendar;
        }

        public function getCalendar() {
                return $this->myTabCalendar;
        }
}
?>