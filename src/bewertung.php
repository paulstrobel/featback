<?php

include 'dbconnection.php';
/*Verbindung zu unserer Datenbank herstellen*/
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);

$mahlzeit_id = mysqli_real_escape_string($mysqli, $_REQUEST['mahlzeit_id']);
$bewertung = mysqli_real_escape_string($mysqli, $_REQUEST['bewertung']);
$kommentar = mysqli_real_escape_string($mysqli, $_REQUEST['kommentar']);
$bewertungID = rand(pow(10, 3), pow(10, 4)-1);
$ip_user = '46.223.128.1';
/*$mahlzeit_id = '1234';*/

$query = "INSERT INTO Essensbewertung(ID, MahlzeitID, Bewertung, Kommentar, IP) VALUES ('$bewertungID','$mahlzeit_id','$bewertung','$kommentar','$ip_user')";
	if(mysqli_query($mysqli, $query)){
		echo "Danke f&uuml;r deine Bewertung :)!<br/>";
	} else{
		echo "ERROR - Folgender Fehler: " . mysqli_error($mysqli);
	}
	echo "<a href=\"index.php\">Zurück zur Startseite</a>";

$mysqli->close();

?>