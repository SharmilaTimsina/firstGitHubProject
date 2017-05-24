$(document).ready(function() {
	
	$(":file").filestyle({
		placeholder: "No files",
		buttonText: "Choose .CSV and Upload"
	});
	
	var table = $('#tableJump').DataTable({
		"order": [[ 0, "asc" ]]
	});
	

	$(document).delegate(':file', 'change', function() {
		if (confirm('Are you sure you want to save this Jumps?')) {
			submitform();
		} else {
			$(":file").filestyle('clear');	
		}
	});
	
	$('label').on("click", function(event) {
		$(":file").filestyle('clear');	
	});

	function submitform() {
		event.preventDefault();

		if(true) {
			$("#colmd12table").hide();
			$("#status2").show();
			$(":file").filestyle('disabled', true);
		
			var fileInput = document.getElementById('filestyle-0');
			var file = fileInput.files[0];
			var formData = new FormData();
			formData.append('file', file);
			
			$.ajax({
				url: 'http://mobisteinreport.com/jump/uploadCsv',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					dataTable = data;
					$(":file").filestyle('disabled', false);
				},
				error: function(response) {
					alert("error");
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
		} else {
			alert("File not found.");
		}
			
		var dataTable = '';
		$(document).ajaxStop(function() {			
			table.destroy();

			$("#tbodyJump").empty();
		
			$("#tbodyJump").html(dataTable);

			table = $('#tableJump').DataTable({
				"order": [[ 0, "asc" ]]
			});
			
			$(":file").filestyle('disabled', false);
			$("#status2").hide();
			$("#colmd12table").show();
		});
	}
	
});