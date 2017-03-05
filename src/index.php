<?php

require_once('../wp-config.php');
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);
echo "<html><head><title>featback</title>";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\">
        <script type=\"text/javascript\" src=\"client-side-controller.js \"></script>
        </head><body>";
echo "  <center><div class=\"container\">
        <div class=\"header\"><h1>f-eat-back</h1>
        <h2>Isst du noch oder geniesst du schon?</h2></div>";

/* === Daten ===*/
$today = date('Y-m-d');
$weekday = date('N');

/* ========================Aktuelles Speiseplandatum==================== */
echo "  
        <div class=\"fastinsight\">";/*
$query = "SELECT Count(*) as Anzahl FROM wp_users";
if ($result = $mysqli->query($query)) {
    $row = $result->fetch_row();*/
    echo "<h3>Essen am " . $today /* . $row[0] . */ . "</h3>";
    /*
    $result->free();
}*/
echo "</div><div class=\"content\">";

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


/*File_get_contents wird vermutlich von meinem HOST geblockt*/
/*$json_string = file_get_contents('http://openmensa.org/api/v2/canteens/33/days/2017-03-02/meals.json/');
*/

echo "<form id=\"form-mission\" method=\"post\" action=\"index.php\">
    <ul>
    <li>
        <select name=\"membre\">
            <option value=0> Montag </option>
            <option value=1> Dienstag </option>
            <option value=2> Mittwoch </option>
            <option value=3> Donnerstag </option>
            <option value=4> Freitag </option>
        </select>
    </li>

    <!--Content-->
    <li><textarea name=\"texte\" id=\"text_area\" ></textarea></li>

    <!-- Submit -->
    <li class=\"except\"><input type=\"submit\" value=\"Submit\" /></li>
    </ul>

</form>";

/*Daten für Mensa ziehen*/
$mensa = 33; /*33 = DHBW Karlsruhe*/
$mensa_information = "http://openmensa.org/api/v2/canteens/" . $mensa . ".json/";
$mensa_information_json_string = get_data($mensa_information);
$imensa = json_decode($mensa_information_json_string, true);

$status = true;
if($weekday > 5){
    $status = false;
}

echo "<b>Ausgew&auml;hlte Mensa:</b>";
echo "<br/><h3>";
print_r($imensa["name"]);
echo "</h3>";
echo "Heute ";
if($status){
    echo "offen";
}else{
    echo "geschlossen";
}
echo "<hr/>";

/*Datum ziehen und Tage addieren / substrahieren */
/*$today = getdate();
print_r($today);
$today = $today[year] . "-" . $today[mon] . "-" . $today[mday];
echo("<br/>today: " . $today . "<br/>");*/

/*Datum des Speiseplans auswählen*/
$date_meals = $today;

/*Daten für Mahlzeiten ziehen*/
$meals_on_a_specific_date = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals . "/meals.json/";
$json_string = get_data($meals_on_a_specific_date);
$parsed_json_string = json_decode($json_string, true);

/*Mahlzeiten anzeigen*/
echo "<b>Essen 1:</b>";
echo "<br/><h3>";
print_r($parsed_json_string[0]["name"]);
echo "</h3>";
echo "<b>Preis für Studenten:</b> ";
print_r($parsed_json_string[0]["prices"]["students"]);
echo "<br/><br/><hr/>";

echo "<b>Essen 2:</b>";
echo "<br/><h3>";
print_r($parsed_json_string[4]["name"]);
echo "</h3>";
echo "<b>Preis für Studenten:</b> ";
print_r($parsed_json_string[4]["prices"]["students"]);
echo "<br/><br/><hr/>";

echo "<b>Essen 3:</b>";
echo "<br/><h3>";
print_r($parsed_json_string[6]["name"]);
echo "</h3>";
echo "<b>Preis für Studenten:</b> ";
print_r($parsed_json_string[6]["prices"]["students"]);
echo "<br/><br/><hr/>";

echo "Alle Mahlzeiten eines Tages (JSON-String): <br/><br/>";
print_r($parsed_json_string);
echo "<hr/>";

/*
$json_string2 = file_get_contents('meals.json');
$parsed_json_string2 = json_decode($json_string2, true);
echo "JSON als lokale Datei:<br/><br/>";
print_r($parsed_json_string2[0]);

echo "<hr/>";

$json_string3 = "[{\"id\":2453835,\"name\":\"Penne Arrabiata Reibekäse Beilagensalat[1,2,3,Ei,Gl,ML,Se,Sn,So]\",\"category\":\"Wahlessen 1\",\"prices\":{\"students\":2.6,\"employees\":3.2,\"pupils\":2.95,\"others\":3.9},\"notes\":[]},{\"id\":2453836,\"name\":\"Verschiedene Dessert[1,ML]\",\"category\":\"Wahlessen 1\",\"prices\":{\"students\":0.95,\"employees\":0.95,\"pupils\":0.95,\"others\":1.2},\"notes\":[]},{\"id\":2404924,\"name\":\"Teigwaren[Gl]\",\"category\":\"Wahlessen 1\",\"prices\":{\"students\":0.75,\"employees\":0.75,\"pupils\":0.75,\"others\":0.95},\"notes\":[]},{\"id\":2404925,\"name\":\"Tagessuppe\",\"category\":\"Wahlessen 1\",\"prices\":{\"students\":0.45,\"employees\":0.45,\"pupils\":0.45,\"others\":0.65},\"notes\":[]},{\"id\":2453837,\"name\":\"Gebratene Hähnchenkeule Ketchup[Sn]\",\"category\":\"Wahlessen 2\",\"prices\":{\"students\":2.1,\"employees\":3.1,\"pupils\":2.45,\"others\":4.6},\"notes\":[]},{\"id\":2453838,\"name\":\"Pommes\",\"category\":\"Wahlessen 2\",\"prices\":{\"students\":0.95,\"employees\":0.95,\"pupils\":0.95,\"others\":1.2},\"notes\":[]},{\"id\":2404928,\"name\":\"2 Eieromlette Rahmspinat Kartoffeln[1,Ei,Gl,ML]\",\"category\":\"Wahlessen 3\",\"prices\":{\"students\":2.6,\"employees\":3.2,\"pupils\":2.95,\"others\":3.9},\"notes\":[]},{\"id\":2404929,\"name\":\"Salatbuffet überwiegend vegetarisch[1,3,4,7,8,ML,Sf]\",\"category\":\"Wahlessen 3\",\"prices\":{\"students\":0.83,\"employees\":1.14,\"pupils\":0.96,\"others\":1.66},\"notes\":[]},{\"id\":2404930,\"name\":\"Beilagensalat[Ei,Se,Sn,So]\",\"category\":\"Wahlessen 3\",\"prices\":{\"students\":0.8,\"employees\":0.8,\"pupils\":0.8,\"others\":1.0},\"notes\":[]},{\"id\":2404931,\"name\":\"Mischgemüse[1]\",\"category\":\"Wahlessen 3\",\"prices\":{\"students\":0.8,\"employees\":0.8,\"pupils\":0.8,\"others\":1.0},\"notes\":[]}]";
$parsed_json_string3 = json_decode($json_string3, true);
echo "JSON als PHP Variable:<br/><br/>";
print_r($parsed_json_string3[0]);
*/

echo "</div><div class=\"content\">";

/* ========================Speißen Tabelle==================== */
echo "<h3 id=\"allemitglieder\">Spei&szlig;en Tabelle:</h3>";
$query = "SELECT P.user_id as ID, P.value as Name, S.value as Status, G.value as Geburtsdatum, U.user_email as Email, U.user_registered as Registrierungsdatum 
FROM wp_bp_xprofile_data as P, wp_bp_xprofile_data as S, wp_bp_xprofile_data as G, wp_users as U 
WHERE P.field_id = 1 
AND S.field_id = 13
AND G.field_id = 6 
AND P.user_id = U.ID 
AND S.user_id = U.ID 
AND G.user_id = U.ID
ORDER BY U.user_registered DESC";
if ($result = $mysqli->query($query)) {
    /* fetch associative array */
    echo "<table>";
    echo "<tr id=\"tableheader\"><th>ID</th><th>Name</th><th>Status</th><th>E-Mail Adresse</th><th>Registriert am</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Name"] . "</td><td>" . $row["Status"] . "</td><td>" . $row["Email"] . "</td><td>" . $row["Registrierungsdatum"] . "</td></tr>";
    }
    echo "</table>";
    /* free result set */
    $result->free();
}

/* ============================================ */
echo "  </div>
        <div class=\"footer\">&copy; 2017 featback</div>
        </div></center>
        </body>
        </html>";

/* close connection */
$mysqli->close();

?>