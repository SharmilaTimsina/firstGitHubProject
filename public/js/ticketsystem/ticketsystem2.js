
var table = null;

fillTable(jsonTable);

function fillTable(jsonTable2) {
	var jsonGlobal = $.parseJSON(jsonTable2);

	var pageedit = '';
	if(typeEdit == 1) {
		pageedit = 'editticket_itsdes'
	} else if(typeEdit == 2) {
		pageedit = 'editTicket'
	}

	var rows = '';
	for (var i = 0; i < jsonGlobal.length; i++) {
		var atagedit = '<a href="/ticketsystem2/' + pageedit + '?idTicket=' + jsonGlobal[i].id + '"><img style="cursor: pointer;"  title="" class="icontable" class="modalIcon44" src="/img/iconEdit.svg"></a>'

		rows += '<tr>'
				+ '<td>' + jsonGlobal[i].id + '</td>'
				+ '<td>' + jsonGlobal[i].requester + '</td>'
				+ '<td>' + jsonGlobal[i].assignedto + '</td>'
				+ '<td>' + jsonGlobal[i].incharge + '</td>'
				+ '<td>' + jsonGlobal[i].subject.replace(/\\\"/g, '"').replace(/&#039/g, "'") + '</td>'
				+ '<td>' + getValueElement("prioritySB", jsonGlobal[i].priority) + '</td>'
				+ '<td><span style="display: none;">' + jsonGlobal[i].status + '</span>' + getValueElement("statusSB", jsonGlobal[i].status) + '</td>'
				+ '<td>' + jsonGlobal[i].created_at + '</td>'
				+ '<td>' + jsonGlobal[i].deadline + '</td>'
				+ '<td>' + getValueElement("typeSB", jsonGlobal[i].type) + '</td>'
				+ '<td>' + atagedit + '</td></tr>';
	}

	if(typeof table != 'undefined' && table != null)
		table.destroy();
	
	$('#tbodyTicket').empty();
	$("#tbodyTicket").append(rows);
	table = $('#tableTicketsUserSide').DataTable( {
		  "pageLength": 100,
		  "order": [[ 6, 'asc' ], [ 7, 'asc' ]]
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
		$( '.search-box-sel-all' ).unbind();

		var formData = new FormData();	
		formData.append('priority' , getSumoSelects("#prioritySB", 1));
		formData.append('status' , getSumoSelects("#statusSB", 1));
		formData.append('type' , getSumoSelects("#typeSB", 1));

		$.ajax({
			url: 'ticketsystem2/setFilter',
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