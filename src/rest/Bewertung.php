<?php

header('Access-Control-Allow-Origin: *'); 

include '../dbconnection.php';
/*Verbindung zu unserer Datenbank herstellen*/
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);

/*
$method = $_SERVER['RESQUEST_METHOD']
if ($method == 'POST') {
	postBewertung();
} elseif ($method == 'GET') {
	// Platzhalter für eine Methode um Bewertung zu laden
} elseif ($method == 'PUT') {
	// Platzhalter für eine  Methode um Bewertung zu aktualisieren
} elseif ($method == 'DELETE') {
	// Platzhalter für eine Methode um Bewertung zu löschen
} 

function postBewertung{
	*/
	$mahlzeit_id = mysqli_real_escape_string($mysqli, $_REQUEST['mahlzeit_id']);
	$bewertung = mysqli_real_escape_string($mysqli, $_REQUEST['bewertung']);
	$kommentar = mysqli_real_escape_string($mysqli, $_REQUEST['kommentar']);
	$bewertungID = rand(pow(10, 3), pow(10, 4)-1); // TODO: Feld in phpMyAdmin auf Auto_INCREMENT stellen dann werden IDs automatisch vergeben
	$ip_user = '46.223.128.1';
	/*$mahlzeit_id = '1234';*/

	//TODO: Variablen gegen Mysql-Injection prüfen
	//TODO: Keine leeren Einträge

	if($bewertung == 0){
		$message = "wähle eine Bewertung aus";
		echo "<script type='text/javascript'>alert('" . $message . "');</script>";
	}else{
		$query = "INSERT INTO Essensbewertung(ID, MahlzeitID, Bewertung, Kommentar, IP) VALUES ('$bewertungID','$mahlzeit_id','$bewertung','$kommentar','$ip_user')";
			if(mysqli_query($mysqli, $query)){
				echo "Danke f&uuml;r deine Bewertung :)!<br/>";
			} else{
				echo "ERROR - Folgender Fehler: " . mysqli_error($mysqli);
			}
	}
//}


$mysqli->close();

?>