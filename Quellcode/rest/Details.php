<?php

header('Access-Control-Allow-Origin: *'); 

// Import einer PHP-Datei mit Login-Daten zum Aufbau einer Verbindung zur Datenbank
include '../dbconnection.php';

// Verbindung zu unserer Datenbank herstellen
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);


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

// Datum des Speiseplans auswählen
$date_meals = $_GET["Day"];

// 33 = Mensa der DHBW Karlsruhe*
$mensa = 33; 

//Daten für Mahlzeiten über die openMensa Api im JSON Format holen
$meals_on_a_specific_date = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals . "/meals.json/";
$json_string = get_data($meals_on_a_specific_date);
$parsed_json_string = json_decode($json_string, true);

// Hilfsvariablen zur Differenzierung zwischen Hauptbestandteilen von Mahlzeiten und Beilagen
$wahlessen = [
    0 => "Wahlessen 1",
    1 => "Wahlessen 2",
    2 => "Wahlessen 3",
];

// Differenzierung zwischen Mahlzeiten und Beilagen
for ($i = 0; $i <= 2; $i++) {
    $j=0;
    foreach($parsed_json_string as $item) {  
        if($item["category"] == $wahlessen[$i]){
            $mahlzeit[$i][$j] = $item;
            $j = $j + 1;
        }
    }
}

// ======================== Anzeige von Mahlzeit-Name, Beilagen und Preisen ====================
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
                    echo "<br/><b>Preise</b><br/>";
                    echo "Studenten: " . $mahlzeit[$a][0]["prices"]["students"] . "€";
                    echo " | Angestellte: " . $mahlzeit[$a][0]["prices"]["employees"] . "€";
                    echo " | Schüler: " . $mahlzeit[$a][0]["prices"]["pupils"] . "€";
                    echo " | Andere: " . $mahlzeit[$a][0]["prices"]["others"] . "€";
                echo "<hr/>";

// ======================== Durchschnittliche Bewertung abrufen, berechnen und anzeigen ====================
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


// ======================== Formular für die Bewertung ==================== */
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

// =================== Alle bisherigen Bewertungen anzeigen =========================
                echo "
                <div class=\"pop-content-bewertungen\">
                <hr/>Was andere sagen..<br/><br/>";
                $query = "SELECT Bewertung, Kommentar FROM Essensbewertung WHERE MahlzeitID = $mahlzeit_id";
                if ($result = $mysqli->query($query)) {
                    echo "<div class=\"bewertungen-tabelle\">";
                    while ($row = $result->fetch_assoc()) {
                        echo "<i>" . $row["Kommentar"] . "</i> [" . $row["Bewertung"] . "]<br/>";
                    }
                    $result->free();
                }
                echo "</div></div></div>";
        }

// Schließen der Datenbankverbindung
$mysqli->close();

?>
