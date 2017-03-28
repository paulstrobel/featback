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

/*Datum des Speiseplans auswählen*/
$date_meals = date('Y-m-d');
$mensa = 33; /*33 = DHBW Karlsruhe*/

/*Daten für Mahlzeiten ziehen*/
$meals_on_a_specific_date = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals . "/meals.json/";
$json_string = get_data($meals_on_a_specific_date);
/* JSON String ausgeben */
echo $json_string;

$mysqli->close();

?>