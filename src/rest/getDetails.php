<?php

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
                for ($b = 1; $b <= count($mahlzeit[$a])-1; $b++) {
                    if (strpos($mahlzeit[$a][$b]["name"], '[') !== false) {
                        echo strstr($mahlzeit[$a][$b]["name"], '[', true) . "<br/>";
                    }else{
                        echo $mahlzeit[$a][$b]["name"] . '<br/>';
                    }
                }
            echo "<hr/>";
/* ========================Durchschnittliche Bewertung abrufen und berechnen==================== */
            $mahlzeit_id = $mahlzeit[$a][0]["id"]; 
            $query = "SELECT avg(Bewertung) FROM Essensbewertung WHERE MahlzeitID = $mahlzeit_id";
            if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                echo "Durchschnittliche Bewertung: " . $row[0] . "<br/>"; //TODO: Runden
                /* free result set */
                $result->free();
            }
/* ========================Selbst Bewertung abgeben==================== */
            echo "
            <hr/>
            Essen bewerten:<br/>
            <form onsubmit=\"return setBewertung(this)\">
                <input type=\"radio\" name=\"bewertung\" value=\"1\">1</input>
                <input type=\"radio\" name=\"bewertung\" value=\"2\">2</input>
                <input type=\"radio\" name=\"bewertung\" value=\"3\">3</input>
                <input type=\"radio\" name=\"bewertung\" value=\"4\">4</input>
                <input type=\"radio\" name=\"bewertung\" value=\"5\">5</input><br/>
                Kommentar<br/>
                <input class=\"textbox\" type=\"text\" name=\"kommentar\"></input><br/><br/>
                <input type=\"hidden\" name=\"mahlzeit_id\" value=\"" . $mahlzeit_id . "\">
                <input type=\"submit\" value=\"Bewerten\">
            </form>
            ";


            /* ===================Alle bisherigen Bewertungen anzeigen========================= */
            echo "<hr/>Alle Bewertungen:<br/>";
            $query = "SELECT Bewertung, Kommentar FROM Essensbewertung WHERE MahlzeitID = $mahlzeit_id";
            if ($result = $mysqli->query($query)) {
                /* fetch associative array */
                //TODO: if anzahl > 0
                echo "<table>";
                echo "<tr id=\"tableheader\"><th>Bewertung &nbsp;&nbsp;</th><th>Kommentar</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["Bewertung"] . "</td><td>" . $row["Kommentar"] . "</td></tr>";
                }
                echo "</table>";
                /* free result set */
                $result->free();
            }
            echo "</div>";
            echo "</div>";
    }


$mysqli->close();

?>