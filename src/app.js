/** Die Methoden müssen statt window.prompt die tatsächlichen Daten aus der php Datei auslesen 
 * und in einem Kontextfenster darstellen */

function closePopup()
{
    $("#popup").fadeOut('fast');
}

// URL vom Server wo das Back-end liegt
var url = "http://paulstrobel.de/featback/";

$(document).on('click touchstart', '#detail0, #detail1, #detail2', function(event) {
    
    $.get(url + "rest/getDetails.php", function( response ) {
        //console.log(response);

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
        //console.log(response);
    
        $('#generelDetails').html(''
        +'<div class="block">'
        +'    <button class="title" id="detail0">' + response[0]["name"] + '</button>'
        +'</div>'
        +'<div class="block">'
        +'    <button class="title" id="detail1">' + response[3]["name"] + '</button>'
        +'</div>'
        +'<div class="block">'
        +'    <button class="title" id="detail2">' + response[5]["name"] + '</button>'
        +'</div>');

    });

});


/* Bewertung abspeichern */

function setBewertung(form)
{

    console.log(form.bewertung.value + "/" + form.kommentar.value + "/" + form.mahlzeit_id.value);
    alert("Bewertung abgeschickt.");
    
    //TODO: Checken ob felder leer sind

    $.post(url + "rest/setBewertung.php", { bewertung: form.bewertung.value, kommentar: form.kommentar.value, mahlzeit_id: form.mahlzeit_id.value})
    .done(function( data ) {

        console.log(data);

        closePopup();

    });

   return false;
}