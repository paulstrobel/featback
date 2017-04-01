<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

include '../dbconnection.php';

/*Verbindung zu unserer Datenbank herstellen*/
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);

/*
$method = $_SERVER['RESQUEST_METHOD']
if ($method == 'POST') {
    // Platzhalter für eine Methode um eine Übersicht zu speichern
} elseif ($method == 'GET') {
    getOverview();
} elseif ($method == 'PUT') {
    // Platzhalter für eine Methode um eine Übersicht zu aktualisieren
} elseif ($method == 'DELETE') {
    // Platzhalter für eine Methode um eine Übersicht zu löschen
} 

function getOverview{ */
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

    /*Datum des Speiseplans auswählen*/
    $date_meals = date('Y-m-d');

    /*Daten für Mahlzeiten ziehen*/
    $meals_on_a_specific_date = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals . "/meals.json/";
    $json_string = get_data($meals_on_a_specific_date);
    $parsed_json_string = json_decode($json_string, true);

    /*=== Hauptbestandteil der Mahlzeit abspeichern, also das keine Beilagen im Überblick angezeigt werden ===*/
    $wahlessen = [
        0 => "Wahlessen 1",
        1 => "Wahlessen 2",
        2 => "Wahlessen 3",
    ];
    for ($i = 0; $i <= 2; $i++) {
        $j=0;
        foreach($parsed_json_string as $item) {  
            if($item["category"] == $wahlessen[$i]){
                $mahlzeit[$i][$j] = $item;
                $j = $j + 1;
            }
        }
    }

    /*Alle Informationen als Array speichern und dem Frontend (app.js) mit echo zur Verfügung stellen*/
    $overview["datum"] = $date_meals;
    $overview["mensa"] = $imensa;
    $overview["mahlzeit"] = $mahlzeit;

    echo json_encode($overview, true);
//}



$mysqli->close();

?>