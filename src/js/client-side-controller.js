window.onload = function() {
	$('#ddlMember').on('change',function(){
	    var get=$('select option:selected').text();
	    document.getElementById('text_area').value=get;
	});
}