/** Die Methoden müssen statt window.prompt die tatsächlichen Daten aus der php Datei auslesen 
 * und in einem Kontextfenster darstellen */

function closePopup()
{
    $("#popup").fadeOut('fast');
    $("#overlay").fadeOut('fast');
    $('body').css('overflow','auto');
}

// URL vom Server wo das Back-end liegt
var url = "http://paulstrobel.de/featback/";

$(document).on('click', '#detail0, #detail1, #detail2', function(event) {
    //var datum = response["datum"];
    var tagFuerDetails = document.getElementById("datumFuerDetails").innerHTML;
    console.log('übertragener Tag: ' + tagFuerDetails);
    console.log('hallo2');
    $.get(url + "rest/Details.php?Day=" + tagFuerDetails, function( response ) {
        //console.log(response);


        $('body').css('overflow','hidden');

        $("#overlay").fadeIn('slow');
        $("#popup").fadeIn('slow');
        $("#popup").html('<a class="glyphicon glyphicon-remove" href="javascript:void(0);" onclick="closePopup();"></a>'
                        + response );
                
        $("#details0").hide();
        $("#details1").hide();
        $("#details2").hide();

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

        //Wochentag anzeigen
        function getDayOfWeek(date) {
          var dayOfWeek = new Date(date).getDay();    
          return isNaN(dayOfWeek) ? null : ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'][dayOfWeek];
        }
        //Ende Wochentag anzeigen
        //Monat anzeigen
        function getMonthOfYear(date) {
          var monthOfYear = new Date(date).getMonth();    
          return isNaN(monthOfYear) ? null : ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'][monthOfYear];
        }
        //Ende Monat anzeigen


function minusTag(){
    $.get(url + "rest/Overview.php", function( response ) {
        var datum = response["datum"];
        var tag = document.getElementById("tag").innerHTML -1;

        document.getElementById("tag").innerHTML = tag;

        tagImMonat = new Date(datum[tag]).getDate();
        jahr = new Date(datum[tag]).getYear() +1900;

        $('#datum').html(getDayOfWeek(datum[tag])+ ", " + tagImMonat + "." + getMonthOfYear(datum[tag]) + " " + jahr);
        console.log(datum);

        //Wochenende nicht anzeigen
        var ausgewaehlterTag = new Date();
        ausgewaehlterTag.setFullYear(datum[tag].substring(0,4));
        ausgewaehlterTag.setMonth(datum[tag].substring(5,7)-1);
        ausgewaehlterTag.setDate(datum[tag].substring(8,10));
        console.log('ausgewaehlterTag: ' + ausgewaehlterTag);
        if(ausgewaehlterTag.getDay() == 6 || ausgewaehlterTag.getDay() == 0){
            $('#menue-row').html('Mensa geschlossen.');
        }else{
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

function plusTag(){
    $.get(url + "rest/Overview.php", function( response ) {
        var datum = response["datum"];
        var tag = parseInt(document.getElementById("tag").innerHTML) + 1;

        document.getElementById("tag").innerHTML = tag;

        tagImMonat = new Date(datum[tag]).getDate();
        jahr = new Date(datum[tag]).getYear() +1900;

        $('#datum').html(getDayOfWeek(datum[tag])+ ", " + tagImMonat + "." + getMonthOfYear(datum[tag]) + " " + jahr);
        console.log(datum);

        //Wochenende nicht anzeigen
        var ausgewaehlterTag = new Date();
        ausgewaehlterTag.setFullYear(datum[tag].substring(0,4));
        ausgewaehlterTag.setMonth(datum[tag].substring(5,7)-1);
        ausgewaehlterTag.setDate(datum[tag].substring(8,10));
        console.log('Ausgewähltes Datum: ' + ausgewaehlterTag);
        if(ausgewaehlterTag.getDay() == 6 || ausgewaehlterTag.getDay() == 0){
            $('#menue-row').html('Mensa geschlossen.');
        }else{
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



/* index.html das erste mal aufgerufen */
$(function() {
  //alert("first call => lade Speisennamen und Bewertung");

    $.get(url + "rest/Overview.php", function( response ) {
        var mensa = JSON.parse(response["mensa"]);
        $('#mensaname').html(mensa.name);

        var datum = response["datum"];
        console.log('Ausgewähltes Datum: ' + datum[10]);
        var tag = 10;

        document.getElementById("tag").value = tag;

        tagImMonat = new Date(datum[tag]).getDate();
        jahr = new Date(datum[tag]).getYear() +1900;

        $('#datum').html(getDayOfWeek(datum[tag])+ ", " + tagImMonat + "." + getMonthOfYear(datum[tag]) + " " + jahr);
        console.log(datum);

        //Wochenende nicht anzeigen
        var ausgewaehlterTag = new Date();
        ausgewaehlterTag.setFullYear(datum[tag].substring(0,4));
        ausgewaehlterTag.setMonth(datum[tag].substring(5,7)-1);
        ausgewaehlterTag.setDate(datum[tag].substring(8,10));
        console.log('ausgewaehlterTag: ' + ausgewaehlterTag);
        if(ausgewaehlterTag.getDay() == 6 || ausgewaehlterTag.getDay() == 0){
            $('#menue-row').html('Mensa geschlossen.');
        }else{
            $('#datumFuerDetails').html(datum[tag]);
            var mahlzeiten = response["mahlzeit"];
            console.log(mahlzeiten);
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


/* Bewertung abspeichern */

function setBewertung(form)
{

    if(form.bewertung.value == ""){
        alert("Wähle erst eine Bewertung aus.");
    }else{
        console.log(form.bewertung.value + "/" + form.kommentar.value + "/" + form.mahlzeit_id.value);
        alert("Bewertung abgeschickt.");

        //TODO: Checken ob felder leer sind

        $.post(url + "rest/Bewertung.php", { bewertung: form.bewertung.value, kommentar: form.kommentar.value, mahlzeit_id: form.mahlzeit_id.value})
        .done(function( data ) {

            console.log(data);

            closePopup();

        });
    }
    

   return false;
}