<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

include '../dbconnection.php';

/*Verbindung zu unserer Datenbank herstellen*/
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);

/* gets the data from a URL */
function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/*Daten für Mensa ziehen*/
$mensa = 33; /*33 = DHBW Karlsruhe*/
$mensa_information = "http://openmensa.org/api/v2/canteens/" . $mensa . ".json/";
$mensa_information_json_string = get_data($mensa_information);
$imensa = $mensa_information_json_string;

$wahlessen = [
    0 => "Wahlessen 1",
    1 => "Wahlessen 2",
    2 => "Wahlessen 3",
];

/*Mahlzeiten von vorgestern bis übermorgen laden und in Variable speichern*/
$z = -10;
for ($k = 0; $k <= 20; $k++) {  
        $date_meals_day[$k] = date('Y-m-d',strtotime("$z days")); 
        $meals_on_a_specific_date[$k] = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals_day[$k] . "/meals.json/";
        $json_string[$k] = get_data($meals_on_a_specific_date[$k]);
        $parsed_json_string[$k] = json_decode($json_string[$k], true);

        for ($i = 0; $i <= 2; $i++) {
            $j=0;
            foreach($parsed_json_string[$k] as $item) {  
                 if($item["category"] == $wahlessen[$i]){
                    $mahlzeit[$k][$i][$j] = $item;
                    $j = $j + 1;
                }
            }
        }
    $z++;
}


/*Alle Informationen als Array speichern und dem Frontend (app.js) mit echo zur Verfügung stellen*/
$overview["datum"] = $date_meals_day;
$overview["mensa"] = $imensa;
$overview["mahlzeit"] = $mahlzeit;

echo json_encode($overview, true);

$mysqli->close();

?>