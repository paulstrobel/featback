<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// Import einer PHP-Datei mit Login-Daten zum Aufbau einer Verbindung zur Datenbank
include '../dbconnection.php';

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method == 'POST') {
    // Platzhalter für eine Methode, um Übersicht zu speichern
} elseif ($method == 'GET') {
    getOverview();
} elseif ($method == 'PUT') {
	// Platzhalter für eine Methode, um Übersicht zu aktualisieren
} elseif ($method == 'DELETE') {
	// Platzhalter für eine Methode, um Übersicht zu löschen
} 

function getOverview() {
    // Verbindung zu unserer Datenbank herstellen
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysqli_select_db($mysqli, DB_NAME);

    // Daten für Mensa ziehen
    // Mensa Nummer 33 ist die Mensa Enzbergerstraße Karlsruhe -> Mensa der DHBW Karlsruhe
    $mensa = 33;
    $mensa_information = "http://openmensa.org/api/v2/canteens/" . $mensa . ".json/";
    $mensa_information_json_string = get_data($mensa_information);
    $imensa = $mensa_information_json_string;

    // Hilfsvariablen zur Differenzierung zwischen Hauptbestandteilen von Mahlzeiten und Beilagen
    $wahlessen = [
        0 => "Wahlessen 1",
        1 => "Wahlessen 2",
        2 => "Wahlessen 3",
    ];

    // Mahlzeiten von vor 10 Tagen bis 10 Tage nach heute laden und in Variable speichern
    $z = -10;
    for ($k = 0; $k <= 20; $k++) {  
            $date_meals_day[$k] = date('Y-m-d',strtotime("$z days")); 
            $meals_on_a_specific_date[$k] = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals_day[$k] . "/meals.json/";
            $json_string[$k] = get_data($meals_on_a_specific_date[$k]);
            $parsed_json_string[$k] = json_decode($json_string[$k], true);

            // Differenzierung zwischen Mahlzeiten und Beilagen
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


    // Alle Informationen als Array speichern und dem Frontend (app.js) mit echo zur Verfügung stellen
    $overview["datum"] = $date_meals_day;
    $overview["mensa"] = $imensa;
    $overview["mahlzeit"] = $mahlzeit;

    // Übertragung der Informationen ans Frontend -> app.js
    echo json_encode($overview, true);

    // Schließen der Datenbankverbindung
    $mysqli->close();
}

// Funktion, mit der eine API zur openMensa-API aufgebaut werden kann, die nicht von unserem Hosting-Provider geblockt wird
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
?>