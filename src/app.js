/** Die Methoden müssen statt window.prompt die tatsächlichen Daten aus der php Datei auslesen 
 * und in einem Kontextfenster darstellen */

$(document).on('click touchstart', '#detail1', function() {
window.prompt("Details zum ersten Essen");
});

$(document).on('click touchstart', '#detail2', function() {
window.prompt("Details zum zweiten Essen");
});

$(document).on('click touchstart', '#detail3', function() {
window.prompt("Details zum dritten Essen");
});