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
    
    $.get(url + "rest/getDetails.php", function( response ) {
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


/* index.html das erste mal aufgerufen */
$(function() {
  //alert("first call => lade Speisennamen und Bewertung");

    $.get(url + "rest/getOverview.php", function( response ) {
        var mensa = JSON.parse(response["mensa"]);
        $('#mensaname').html(mensa.name);

        $('#datum').html(response["datum"]);

        //var mahlzeiten = JSON.parse(response["mahlzeit"]);
        var mahlzeiten = response["mahlzeit"];
        $('#menue-row').html(''
        +'<div class="row">'
        +'  <div class="col-lg-4 col-centered">'
        +'    <button class="menue-button" id="detail0">' + mahlzeiten[0][0]["name"].split("[").shift() + '</button>'
        +'  </div>'
        +'  <div class="col-lg-4 col-centered">'
        +'    <button class="menue-button" id="detail1">' + mahlzeiten[1][0]["name"].split("[").shift() + '</button>'
        +'  </div>'
        +'  <div class="col-lg-4 col-centered">'
        +'    <button class="menue-button" id="detail2">' + mahlzeiten[2][0]["name"].split("[").shift() + '</button>'
        +'  </div>'
        +'</div>');

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

        $.post(url + "rest/setBewertung.php", { bewertung: form.bewertung.value, kommentar: form.kommentar.value, mahlzeit_id: form.mahlzeit_id.value})
        .done(function( data ) {

            console.log(data);

            closePopup();

        });
    }
    

   return false;
}