<?php
/*Diesen Abschnitt später löschen*/
require_once('../wp-config.php');
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

/*Mahlzeiten anzeigen
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
echo "<hr/>";*/

echo "
<span class=\"container\">
    <div class=\"block\">
        <div class=\"title\">" . $parsed_json_string[0]["name"] . "</div>
        <div class=\"smalltitle\"></div>
        <div class=\"content\">
            Zusatzinfos
        </div>
        <div class=\"reference\"><a href=\"#\">mehr Information</a></div>
    </div>
    <div class=\"block\">
        <div class=\"title\">" . $parsed_json_string[4]["name"] . "</div>
        <div class=\"smalltitle\"></div>
        <div class=\"content\">
            Zusatzinfos
        </div>
        <div class=\"reference\"><a href=\"#\">mehr Informationen</a></div>
    </div>
    <div class=\"block\">
        <div class=\"title\">" . $parsed_json_string[6]["name"] . "</div>
        <div class=\"smalltitle\"></div>
        <div class=\"content\">
            Zusatzinfos
        </div>
        <div class=\"reference\"><a href=\"#\">mehr Informationen</a></div>
    </div>
</span>

";
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



/*Datum ziehen und Tage addieren / substrahieren */
/*$today = getdate();
print_r($today);
$today = $today[year] . "-" . $today[mon] . "-" . $today[mday];
echo("<br/>today: " . $today . "<br/>");*/

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

/* close connection */
$mysqli->close();

?>