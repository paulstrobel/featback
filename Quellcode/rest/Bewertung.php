<?php

header('Access-Control-Allow-Origin: *'); 

// Import einer PHP-Datei mit Login-Daten zum Aufbau einer Verbindung zur Datenbank
include '../dbconnection.php';

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method == 'POST') {
	postBewertung();
} elseif ($method == 'GET') {
	// Platzhalter für eine Methode um Bewertung zu laden
} elseif ($method == 'PUT') {
	// Platzhalter für eine  Methode um Bewertung zu aktualisieren
} elseif ($method == 'DELETE') {
	// Platzhalter für eine Methode um Bewertung zu löschen
} 

function postBewertung() {
	// Verbindung zu unserer Datenbank herstellen
	$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
	mysqli_select_db($mysqli, DB_NAME);

	// Informationen des Eingabe-Formulars in Variablen speichern
	$mahlzeit_id = mysqli_real_escape_string($mysqli, $_REQUEST['mahlzeit_id']);
	$bewertung = mysqli_real_escape_string($mysqli, $_REQUEST['bewertung']);
	$kommentar = mysqli_real_escape_string($mysqli, $_REQUEST['kommentar']);
	$bewertungID = rand(pow(10, 3), pow(10, 4)-1);
	$ip_user = '46.223.128.1';

	// Verbesserungsspotential: Variablen gegen Mysql-Injection prüfen

	// Überprüfung, ob Nutzer eine Bewertung ausgewählt hat
	if ($bewertung == 0) {
		// Falls nein, Aufruf zur Bewertung
		$message = "Wähle eine Bewertung aus";
		echo "<script type='text/javascript'>alert('" . $message . "');</script>";
	} else {
		// Falls ja, Abspeicherung der Bewertung in die Datenbank
		$query = "INSERT INTO Essensbewertung(ID, MahlzeitID, Bewertung, Kommentar, IP) VALUES ('$bewertungID','$mahlzeit_id','$bewertung','$kommentar','$ip_user')";
		if(mysqli_query($mysqli, $query)){
			echo "Danke f&uuml;r deine Bewertung :)!<br/>";
		} else{
			echo "ERROR - Folgender Fehler: " . mysqli_error($mysqli);
		}
	}

	// Schließen der Datenbankverbindung
	$mysqli->close();
}
?>