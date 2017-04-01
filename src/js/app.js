
// Funktion für den Pop-Up Einblendungs- & Ausblendungseffekt 
function closePopup()
{
    $("#popup").fadeOut('fast');
    $("#overlay").fadeOut('fast');
    $('body').css('overflow','auto');
}

// URL vom Server wo das Backend liegt
var url = "http://paulstrobel.de/featback/";

// Funktion für die Darstellung der Mahlzeiten-Details
$(document).on('click', '#detail0, #detail1, #detail2', function(event) {
    
    // Erfragen des aktuell angezeigten Tages, um für diesen Tag anschließend die Details zu laden
    var tagFuerDetails = document.getElementById("datumFuerDetails").innerHTML;

    // Überprüfung, ob Tag abgefragt werden konnte - Konsolenausgabe des aktuellen Tages
    console.log('übertragener Tag: ' + tagFuerDetails);

    // Laden der Details für den aktuellen Tag für alle 3 Mahlzeiten
    $.get(url + "rest/Details.php?Day=" + tagFuerDetails, function( response ) {

        // Pop-Up Effekte
        $('body').css('overflow','hidden');
        $("#overlay").fadeIn('slow');
        $("#popup").fadeIn('slow');
        $("#popup").html('<a class="glyphicon glyphicon-remove" href="javascript:void(0);" onclick="closePopup();"></a>'
                        + response );

        // Weitere Pop-Up Effekte
        $("#details0").hide();
        $("#details1").hide();
        $("#details2").hide();

        // Swith Case zur Auswahl einer von drei Mahlzeiten
        switch(event.target.id){
            case'detail0': 
                $("#details0").show();
            break;
            case'detail1': 
                $("#details1").show();
            break;
            case'detail2': 
                $("#details2").show();
            break;
        }
    });
});

// Funktion zur Anzeige des aktuellen Wochentags
function getDayOfWeek(date) {
    var dayOfWeek = new Date(date).getDay();    
    return isNaN(dayOfWeek) ? null : ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'][dayOfWeek];
}

// Funktion zur Anzeige des aktuellen Monats
function getMonthOfYear(date) {
    var monthOfYear = new Date(date).getMonth();    
    return isNaN(monthOfYear) ? null : ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'][monthOfYear];
}

// Funktion für das Laden des Überblicks des vorherigen Tages
function minusTag(){

    // Abfragen von Informationen aus der Overview.php (Überblick über die Mahlzeiten)
    $.get(url + "rest/Overview.php", function( response ) {

        // Vorherigen Tag anzeigen (Aktueller Tag minus 1)
        var datum = response["datum"];
        var tag = document.getElementById("tag").innerHTML -1;
        document.getElementById("tag").innerHTML = tag;

        // Schöne Darstellung des Datums im Format "Dienstag, 18.April 2017"
        tagImMonat = new Date(datum[tag]).getDate();
        jahr = new Date(datum[tag]).getYear() +1900;
        $('#datum').html(getDayOfWeek(datum[tag])+ ", " + tagImMonat + "." + getMonthOfYear(datum[tag]) + " " + jahr);
        
        // Überprüfung der Darstellung des Datums
        console.log(datum);

        // Maßnahme zur Meldung des Mensa-Status sowohl an Wochentagen als auch an Wochenendtagen
        // Erzeugung eines Datums
        var ausgewaehlterTag = new Date();

        // Abspeicherung des aktuellen Jahres
        ausgewaehlterTag.setFullYear(datum[tag].substring(0,4));

        // Abspeicherung des aktuellen Monats
        ausgewaehlterTag.setMonth(datum[tag].substring(5,7)-1);

        // Abspeicherung des aktuellen Tages
        ausgewaehlterTag.setDate(datum[tag].substring(8,10));

        // Kontrolle des abgespeicherten Datums
        console.log('ausgewaehlterTag: ' + ausgewaehlterTag);

        // Spezielle Nachricht an Samstagen und Sonntagen
        // wenn Samstag oder Sonntag
        if(ausgewaehlterTag.getDay() == 6 || ausgewaehlterTag.getDay() == 0){

            // Manipulation des DOM - Nachricht, dass Mensa geschlossen ist
            $('#menue-row').html('Mensa geschlossen.');

        // wenn Wochentag
        }else{

            // Manipulation des DOM - Anzeige des Überblicks der 3 Mahlzeiten
            $('#datumFuerDetails').html(datum[tag]);
            var mahlzeiten = response["mahlzeit"];
            $('#menue-row').html(''
            +'<div class="row">'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail0">' + mahlzeiten[tag][0][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail1">' + mahlzeiten[tag][1][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail2">' + mahlzeiten[tag][2][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'</div>');
        }
    });
}

// Funktion für das Laden des Überblicks des folgenden Tages
function plusTag(){

    // Abfragen von Informationen aus der Overview.php (Überblick über die Mahlzeiten)
    $.get(url + "rest/Overview.php", function( response ) {

        // Nachfolgenden Tag anzeigen (Aktueller Tag plus 1)
        var datum = response["datum"];
        var tag = parseInt(document.getElementById("tag").innerHTML) + 1;
        document.getElementById("tag").innerHTML = tag;

        // Schöne Darstellung des Datums im Format "Dienstag, 18.April 2017"
        tagImMonat = new Date(datum[tag]).getDate();
        jahr = new Date(datum[tag]).getYear() +1900;
        $('#datum').html(getDayOfWeek(datum[tag])+ ", " + tagImMonat + "." + getMonthOfYear(datum[tag]) + " " + jahr);

        // Überprüfung der Darstellung des Datums
        console.log(datum);

        // Maßnahme zur Meldung des Mensa-Status sowohl an Wochentagen als auch an Wochenendtagen
        // Erzeugung eines Datums
        var ausgewaehlterTag = new Date();

        // Abspeicherung des aktuellen Jahres
        ausgewaehlterTag.setFullYear(datum[tag].substring(0,4));

        // Abspeicherung des aktuellen Monats
        ausgewaehlterTag.setMonth(datum[tag].substring(5,7)-1);

        // Abspeicherung des aktuellen Tages
        ausgewaehlterTag.setDate(datum[tag].substring(8,10));

        // Kontrolle des abgespeicherten Datums
        console.log('ausgewaehlterTag: ' + ausgewaehlterTag);

        // Spezielle Nachricht an Samstagen und Sonntagen
        // wenn Samstag oder Sonntag
        if(ausgewaehlterTag.getDay() == 6 || ausgewaehlterTag.getDay() == 0){

            // Manipulation des DOM - Nachricht, dass Mensa geschlossen ist
            $('#menue-row').html('Mensa geschlossen.');

        // wenn Wochentag
        }else{

            // Manipulation des DOM - Anzeige des Überblicks der 3 Mahlzeiten
            $('#datumFuerDetails').html(datum[tag]);
            var mahlzeiten = response["mahlzeit"];
            $('#menue-row').html(''
            +'<div class="row">'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail0">' + mahlzeiten[tag][0][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail1">' + mahlzeiten[tag][1][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail2">' + mahlzeiten[tag][2][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'</div>');
        }
    });
}



// Funktion für das Laden des Überblicks des aktuellen Tages -> wird beim Start der index.html ausgeführt
$(function() {
  
    // Abfragen von Informationen aus der Overview.php (Überblick über die Mahlzeiten)
    $.get(url + "rest/Overview.php", function( response ) {
        
        // Mensanamen abfragen und durch DOM-Manipulation anzeigen
        var mensa = JSON.parse(response["mensa"]);
        $('#mensaname').html(mensa.name);

        // Aktuellen Tag anzeigen
        var datum = response["datum"];

        // Es werden 21 Tage geladen, +-10 Tage vor/nach dem aktuellen Tag
        console.log('Ausgewähltes Datum: ' + datum[10]);

        // Aktueller Tag ist Tag 10
        var tag = 10;
        document.getElementById("tag").value = tag;

        // Schöne Darstellung des Datums im Format "Dienstag, 18.April 2017"
        tagImMonat = new Date(datum[tag]).getDate();
        jahr = new Date(datum[tag]).getYear() +1900;
        $('#datum').html(getDayOfWeek(datum[tag])+ ", " + tagImMonat + "." + getMonthOfYear(datum[tag]) + " " + jahr);

        // Kontrolle des abgespeicherten Datums
        console.log(datum);

        // Maßnahme zur Meldung des Mensa-Status sowohl an Wochentagen als auch an Wochenendtagen
        // Erzeugung eines Datums
        var ausgewaehlterTag = new Date();

        // Abspeicherung des aktuellen Jahres
        ausgewaehlterTag.setFullYear(datum[tag].substring(0,4));

        // Abspeicherung des aktuellen Monats
        ausgewaehlterTag.setMonth(datum[tag].substring(5,7)-1);

        // Abspeicherung des aktuellen Tages
        ausgewaehlterTag.setDate(datum[tag].substring(8,10));

        // Kontrolle des abgespeicherten Datums
        console.log('ausgewaehlterTag: ' + ausgewaehlterTag);

        // Spezielle Nachricht an Samstagen und Sonntagen
        // wenn Samstag oder Sonntag
        if(ausgewaehlterTag.getDay() == 6 || ausgewaehlterTag.getDay() == 0){

            // Manipulation des DOM - Nachricht, dass Mensa geschlossen ist
            $('#menue-row').html('Mensa geschlossen.');

        // wenn Wochentag
        }else{

            // Manipulation des DOM - Anzeige des Überblicks der 3 Mahlzeiten
            $('#datumFuerDetails').html(datum[tag]);
            var mahlzeiten = response["mahlzeit"];
            $('#menue-row').html(''
            +'<div class="row">'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail0">' + mahlzeiten[tag][0][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail1">' + mahlzeiten[tag][1][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'  <div class="col-lg-4 col-centered">'
            +'    <button class="menue-button" id="detail2">' + mahlzeiten[tag][2][0]["name"].split("[").shift() + '</button>'
            +'  </div>'
            +'</div>');
        }
    });
});


// Funktion zur Abspeicherung der Bewertung
function setBewertung(form)
{
    // wenn Bewertung leer (also keine Bewertung ausgewählt)
    if(form.bewertung.value == ""){

        // Meldung, dass zuerst bewertet werden muss, bevor die Bewertung gespeichert werden kann
        alert("Wähle erst eine Bewertung aus.");

    // wenn Bewertung zwischen 1-5 ausgewählt
    }else{

        // Konsolenausgabe der Bewertungsinformationen, die abgespeichert werden sollen
        console.log(form.bewertung.value + "/" + form.kommentar.value + "/" + form.mahlzeit_id.value);

        // Meldung an Nutzer, dass Bewertung abgesendet wurde
        alert("Bewertung abgeschickt.");

        // Senden der Bewertungsinformationen an das Backend -> Bewertung.php
        $.post(url + "rest/Bewertung.php", { bewertung: form.bewertung.value, kommentar: form.kommentar.value, mahlzeit_id: form.mahlzeit_id.value})
        .done(function( data ) {

            //Überprüfung der Daten
            console.log(data);

            //Automatisches Schließen des Pop-Up-Fensters, sobald eine Bewertung abgegeben wurde.
            closePopup();
        });
    }
   return false;
}