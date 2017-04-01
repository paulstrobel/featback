<?php

header('Access-Control-Allow-Origin: *'); 

include '../dbconnection.php';

/*Verbindung zu unserer Datenbank herstellen*/
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);

/*
$method = $_SERVER['RESQUEST_METHOD']
if ($method == 'POST') {
    // Platzhalter für eine Methode um Details zu speichern
} elseif ($method == 'GET') {
    getDetails();
} elseif ($method == 'PUT') {  
    // Platzhalter für eine Methode um Details zu aktualisieren
} elseif ($method == 'DELETE') {
    // Platzhalter für eine Methode um Details zu löschen
} 

function getDetails{ */
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
    $parsed_json_string = json_decode($json_string, true);


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

    /* ========================Mahlzeit-Name und weitere Informationen==================== */
        for ($a = 0; $a <= count($mahlzeit)-1; $a++) {
            echo "
            <div class=\"block\" id=\"details" . $a . "\">
                <div class=\"pop-title\">" . strstr($mahlzeit[$a][0]["name"], '[', true) . "</div>
                <div class=\"pop-second-title\"></div>
                <div class=\"pop-content\">";
                
                    echo "<b>Beilagen</b><br/>| ";
                    for ($b = 1; $b <= count($mahlzeit[$a])-1; $b++) {
                        if (strpos($mahlzeit[$a][$b]["name"], '[') !== false) {
                            echo strstr($mahlzeit[$a][$b]["name"], '[', true) . " | ";
                        }else{
                            echo $mahlzeit[$a][$b]["name"] . ' | ';
                        }
                    }
                echo "<hr/>";
    /* ========================Durchschnittliche Bewertung abrufen und berechnen==================== */
                echo "<div class=\"pop-content-left\">";
                $mahlzeit_id = $mahlzeit[$a][0]["id"]; 
                $query = "SELECT avg(Bewertung) FROM Essensbewertung WHERE MahlzeitID = $mahlzeit_id";
                if ($result = $mysqli->query($query)) {
                    $row = $result->fetch_row();
                    echo "<div class=\"dbewertung\">" . round($row[0], 1) . "</div><br/>";
                    echo "Ø-Bewertung<br/>";
                    /* free result set */
                    $result->free();
                }
                echo "</div><div class=\"pop-content-right\">";


    /* ========================Selbst Bewertung abgeben==================== */
                echo "Essen bewerten:<br/>
                <form onsubmit=\"return setBewertung(this)\">

                    <span class=\"rating\">
                            <input type=\"radio\" class=\"rating-input\" id=\"". $mahlzeit_id ."rating-input-1-5\" name=\"bewertung\" value=\"5\">
                            <label for=\"". $mahlzeit_id ."rating-input-1-5\" class=\"rating-star\"></label>
                            <input type=\"radio\" class=\"rating-input\" id=\"". $mahlzeit_id ."rating-input-1-4\" name=\"bewertung\" value=\"4\">
                            <label for=\"". $mahlzeit_id ."rating-input-1-4\" class=\"rating-star\"></label>
                            <input type=\"radio\" class=\"rating-input\" id=\"". $mahlzeit_id ."rating-input-1-3\" name=\"bewertung\" value=\"3\">
                            <label for=\"". $mahlzeit_id ."rating-input-1-3\" class=\"rating-star\"></label>
                            <input type=\"radio\" class=\"rating-input\" id=\"". $mahlzeit_id ."rating-input-1-2\" name=\"bewertung\" value=\"2\">
                            <label for=\"". $mahlzeit_id ."rating-input-1-2\" class=\"rating-star\"></label>
                            <input type=\"radio\" class=\"rating-input\" id=\"". $mahlzeit_id ."rating-input-1-1\" name=\"bewertung\" value=\"1\">
                            <label for=\"". $mahlzeit_id ."rating-input-1-1\" class=\"rating-star\"></label>
                    </span>

                    <br/>Kommentar<br/>
                    <input class=\"textbox\" type=\"text\" name=\"kommentar\"></input><br/><br/>
                    <input type=\"hidden\" name=\"mahlzeit_id\" value=\"" . $mahlzeit_id . "\">
                    <input class=\"submit-button\" type=\"submit\" value=\"Bewerten\">
                </form></div></div>
                ";

    /* ===================Alle bisherigen Bewertungen anzeigen========================= */
                echo "
                <div class=\"pop-content-bewertungen\">
                <hr/>Was andere sagen..<br/><br/>";
                $query = "SELECT Bewertung, Kommentar FROM Essensbewertung WHERE MahlzeitID = $mahlzeit_id";
                if ($result = $mysqli->query($query)) {
                    /* fetch associative array */
                    //TODO: if anzahl > 0
                    echo "<div class=\"bewertungen-tabelle\">";
                    while ($row = $result->fetch_assoc()) {
                        echo "<i>" . $row["Kommentar"] . "</i> [" . $row["Bewertung"] . "]<br/>";
                    }
                    /* free result set */
                    $result->free();
                }


                echo "</div></div></div>";
        }

// }
    $mysqli->close();


?>