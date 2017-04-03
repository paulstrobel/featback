<?php

echo("Hallo");

include '../dbconnection.php';
include 'Overview.php';
include 'Details.php';
include 'Bewertung.php';

// Test, ob Mahlzeiten von der API erfolgreich bezogen wurden konnten
echo("huuuuuuuuuuuuuhuuuuuu: " . $meals_on_a_specific_date);

// Test, ob Eintrag in Datenbank funktioniert 
/*$query = "INSERT INTO Essensbewertung(ID, MahlzeitID, Bewertung, Kommentar, IP) VALUES ('12345','12345','5','Unit-test','12345')";
		if(mysqli_query($mysqli, $query)){
			echo "Bewertung erfolgreich";
		} else{
			echo "ERROR - Folgender Fehler: " . mysqli_error($mysqli);
		}*/

?>