
var table = null;

fillTable(jsonTable);

function fillTable(jsonTable2) {
	
	

	var jsonGlobal = $.parseJSON(jsonTable2);

	var rows = '';
	for (var i = 0; i < jsonGlobal.length; i++) {
		var atagedit = '<a href="http://mobisteinreport.com/ticketsystem/editTicket?idTicket=' + jsonGlobal[i].id + '"><img style="cursor: pointer;"  title="" class="icontable" class="modalIcon44" src="http://mobisteinreport.com/img/iconEdit.svg"></a>'

		rows += '<tr>'
				+ '<td>' + jsonGlobal[i].id + '</td>'
				+ '<td>' + jsonGlobal[i].requester + '</td>'
				+ '<td>' + jsonGlobal[i].subject + '</td>'
				+ '<td>' + jsonGlobal[i].incharge + '</td>'
				+ '<td>' + getValueElement("prioritySB", jsonGlobal[i].priority) + '</td>'
				+ '<td>' + getValueElement("typeSB", jsonGlobal[i].type) + '</td>'
				+ '<td>' + getValueElement("statusSB", jsonGlobal[i].status) + '</td>'
				+ '<td>' + jsonGlobal[i].created_at + '</td>'
				+ '<td>' + atagedit + '</td></tr>';
	}

	if(typeof table != 'undefined' && table != null)
		table.destroy();
	
	$('#tbodyTicket').empty();
	$("#tbodyTicket").append(rows);
	table = $('#tableTicketsUserSide').DataTable( {
		  "pageLength": 100
		});			

}



window.searchSelAll = $('.search-box-sel-all').SumoSelect({
    csvDispCount: 3,
    selectAll: true,
    search: true,
    searchText: 'Enter here.',
    okCancelInMulti: false
});

$( '.search-box-sel-all' ).unbind();

function getValueElement(selecBox, key) {
	return $("#" + selecBox + " option[value='" + key + "']").text()
}

$("body").on("click", "#buttonFilterTickets", function() {

		var formData = new FormData();	
		formData.append('priority' , getSumoSelects("#prioritySB", 1));
		formData.append('status' , getSumoSelects("#statusSB", 1));
		formData.append('type' , getSumoSelects("#typeSB", 1));

		$.ajax({
			url: 'ticketsystem/setFilter',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				fillTable(data);

			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$( '.search-box-sel-all' ).unbind();
});