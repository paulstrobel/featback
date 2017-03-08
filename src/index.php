<?php

include 'dbconnection.php';

/*Verbindung zu unserer Datenbank herstellen*/
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($mysqli, DB_NAME);

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>f-eat-back - Die Mensa-Bewertungs Plattform</title>

        <link href='http://fonts.googleapis.com/css?family=Oswald|Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/template.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body id="page-top" data-spy="scroll" data-target="navbar-fixed-top">

    <nav class="navbar navbar-default navbar-fixed-top" > <!--role="navigation"-->
        <div class="container">
            <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar navbar-collapse" aria-expanded="false" aria-controls="navbar">
            <!--button type="button" class="navbar-toggle" data-toggle="collapse" data-target="navbar-collapse"-->
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">f-eat-back</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active page-scroll"><a href="#menue">Menüs</a></li>
                <li class="page-scroll"><a href="#about">Über f-eat-back</a></li>
                <li class="page-scroll"><a href="#contact">Kontakt</a></li>
            </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <section id="menue" class="menue-section">
        <h1>Speisekarte</h1>

<?php


/* === Daten ===*/
$today = date('Y-m-d');
$weekday = date('N');

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
$imensa = json_decode($mensa_information_json_string, true);


/*Datum des Speiseplans auswählen*/
$date_meals = $today;

/*Daten für Mahlzeiten ziehen*/
$meals_on_a_specific_date = "http://openmensa.org/api/v2/canteens/" . $mensa . "/days/" . $date_meals . "/meals.json/";
$json_string = get_data($meals_on_a_specific_date);
$parsed_json_string = json_decode($json_string, true);

echo "<h2>";
print_r($imensa["name"]);
echo "</h2>";

$status = true;
if($weekday > 5){
    $status = false;
}


echo "Heute ";
if($status){
    echo "offen ";
}else{
    echo "geschlossen ";
}
echo "(" . $today . ")<br/>";


/* ========================Mahlzeiten anzeigen==================== */


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

echo "<span class=\"container\">";
/* ========================Mahlzeit-Name und weitere Informationen==================== */
    for ($a = 0; $a <= count($mahlzeit)-1; $a++) {
        echo "
        <div class=\"block\">
            <div class=\"title\">" . $mahlzeit[$a][0]["name"] . "</div>
            <div class=\"smalltitle\"></div>
            <div class=\"content\">";
                for ($b = 1; $b <= count($mahlzeit[$a])-1; $b++) {
                    echo $mahlzeit[$a][$b]["name"] . "<br/>";
                }
            echo "<hr/>";
/* ========================Bisherige Bewertungen abrufen==================== */
            $mahlzeit_id = $mahlzeit[$a][0]["id"]; 
            $query = "SELECT Bewertung, Kommentar FROM Essensbewertung WHERE ID = $mahlzeit_id";
            if ($result = $mysqli->query($query)) {
                $row = $result->fetch_row();
                echo "Bewertung: " . $row[0] . "<br/>";
                echo "Kommentar: " . $row[1] . "<br/>";
                /* free result set */
                $result->free();
            }
/* ========================Selbst Bewertung abgeben==================== */
            $bewertung = 4;
            $kommentar = 'gute_Alternative_zu_Fleisch';
            $ip_user = '462231281';
            $query = "INSERT INTO Essensbewertung(ID, Bewertung, Kommentar, IP) VALUES ('$mahlzeit_id','$bewertung','$kommentar','$ip_user')";
            if ($mysqli->query($query) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
            echo "
            </div>
            <div class=\"reference\"><a href=\"#\">Bewerten</a></div>
        </div>";
    }

?>

    </section>

    <section id="about" class="about-section">
    ...und das die 2. ...
    </section>
    
    <section id="contact" class="contact-section">
    ... die 3. nicht vergessen
    </section>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scrolling-nav.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
</body>
</html>

<?

/*echo "<form id=\"form-mission\" method=\"post\" action=\"index.php\">
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

</form>";*/

/* close connection */
$mysqli->close();

?>