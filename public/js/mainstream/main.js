
$(document).ready(function() {
    var table = $('#tableBulk').DataTable({
		"order": [[ 0, "desc" ]]
	});
		
	var allimg = [];
	var selectedImages = [];
	var selectedImagesInfo = {};
	
	$('#ageF').multipleSelect();

	$('#platformF').on('change', function() {
		
		if($("#platformF option:selected").val() == '0' || $("#platformF option:selected").val() == '') 
			$('#osF').attr('disabled', true);
		else 
			$('#osF').attr('disabled', false);
		
		document.getElementById('osF').selectedIndex = 0;
	});	
	
	
	var totalImages = 0;
	var numberOfImagesSelected = 0;
	$('body').on('click','#tbodygeneralBanners .imgForSelect > .imgTags', function() {
		
		if($(this).closest('.imgForSelect').css('background-color') == 'rgb(184, 134, 11)') {
			$(this).closest('.imgForSelect').css('background-color', 'transparent');
			numberOfImagesSelected--;
			
			var id = $(this).attr("id");
			selectedImages = jQuery.grep(selectedImages, function(value) {
			  return value != id;
			});

		} else {
			$(this).closest('.imgForSelect').css('background-color', 'darkgoldenrod');
			numberOfImagesSelected++;
			
			var id = $(this).attr("id");
			var src = $(this).attr("src");
			var title = $(this).attr("title");
			var id_user = $(this).attr("id_user");

			selectedImages.push(id);
			selectedImagesInfo[id] = { id : id, src: src, title: title, idUser: id_user };
		}

		$("#numberImagesSelected").text(numberOfImagesSelected);
		
		
		updateSelectedImages();
	});	
	
	
	$('#selectAllImages').on('click', function() {
		selectedImages = [];
		selectedImagesInfo = {};
		
		numberOfImagesSelected = totalImages;
		
		$("#tbodygeneralBanners td div").each(function() {
			var id = $("img", this).attr("id");
			var src = $("img", this).attr("src");
			var title = $("img", this).attr("title");
			var id_user = $("img", this).attr("id_user");

			selectedImages.push(id);
			selectedImagesInfo[id] = { id : id, src: src, title: title, idUser: id_user };
			
			$(this).css('background-color', 'darkgoldenrod');
		});
				
		$("#numberImagesSelected").text(numberOfImagesSelected);
		
		updateSelectedImages();
	});	
	
	$('#deselectAllImages').on('click', function() {
		numberOfImagesSelected = 0;
		selectedImages = [];
		
		$("#tbodygeneralBanners td div").each(function() {
			
			$(this).css('background-color', 'transparent');
		});
		
				
		$("#numberImagesSelected").text(numberOfImagesSelected);
		
		updateSelectedImages();
	});	
	

	function updateSelectedImages() {
		$('#tbodychosedBanners').empty();
		
		for ( i = 0; i < selectedImages.length; i++ ) {
			
			var src = selectedImagesInfo[selectedImages[i]].src;
			var title = selectedImagesInfo[selectedImages[i]].title;
			var id_user = selectedImagesInfo[selectedImages[i]].idUser;

			var col = '<div class="col-md-3"><div class="imgSelected"><img data-toggle="popover" title="" data-html="true" data-placement="bottom" data-content="' + id_user + '" data-original-title=""  id_user="' + id_user + '" title="' + title + '" id="' + selectedImages[i] + '" class="imgTags2" src="' + src + '"></div></div>'
			
			$(col).appendTo('#tbodychosedBanners');

			$('.imgTags2').popover({ trigger: "hover" });		
		}
	}
	
	$('body').on('click', '.imgTags2', function() {
		
		var id = $(this).attr('id');
		$(this).closest('.imgSelected').closest('.col-md-3').remove();
		
		numberOfImagesSelected--;
		
		$('#' + id).closest(".imgForSelect").css('background-color', 'transparent');
		
		selectedImages = jQuery.grep(selectedImages, function(value) {
		  return value != id;
		});
		
		$("#numberImagesSelected").text(numberOfImagesSelected);
	});	
	
	
	var json = "";
	var page = -1;
	languageOld = '';
	categoryOld = '';
	useridOld = '';
	last = false;
	$('#searchBannersByTag').on('click', function() {
		
		var language = $("select[name='languageTag']").val();
		var category = $("select[name='categoryTag']").val();
		
		if(language == 0 && category == 0 && $("#inputSearchText").val() == '')
			alert('Choose at least one TAG.');
		else {
				
			if(language != languageOld || category != categoryOld || $("#inputSearchText").val() != useridOld) {
				last = false
				page = 0;
				$('#tbodygeneralBanners').empty();
			} else {
				if(page != -1)
					page = page + 1;
			}

			languageOld = language;
			categoryOld = category;
			useridOld = $("#inputSearchText").val();

			$(document).ajaxStart(function() {
				$('#searching').css("display", "inline-block");
			});
			
			if(last == true)
				return
		
			allimg = [];
			$.ajax({
				url: '/mainstreambulk/searchbanners?language=' + language + '&category=' + category + '&page=' + page + '&inputID=' + $("#inputSearchText").val() ,
				type: 'GET',
				success: function(data) {
					
					json = $.parseJSON(data);
									
					var tr = "<tr>";
					var k = 0;
				
					if(json.length == 0) {
						last = true;
						tr += '<td><p id="noresultslabel">NO RESULTS</p></td>';

						
						$("#noresultslabel").remove();


					}




					else {
						totalImages = json.length;	
						$("#totalImages").text(totalImages);
					}
					
					
					for (i = 0; i < json.length; i++) {	
						var id = json[i].id;
						var hash = json[i].attributes.hash;
						var language = json[i].attributes.language;
						var category = json[i].attributes.category;
						var url_cloudinary = json[i].attributes.url_cloudinary;
						var id_user = json[i].attributes.id_user;
						
						var td = '<td><div class="imgForSelect"><img data-toggle="popover" title="" data-html="true" data-placement="top" data-content="' + id_user + '" data-original-title=""  id_user="' + id_user + '" id="' + hash + '" class="imgTags" src="' + url_cloudinary + '"></div></td>';	
						
						if(k == 3) {
							tr += td;
							$(tr + "</tr>").appendTo('#tbodygeneralBanners');
							k = 0;
							tr = "<tr>";
						} else {
							tr += td;
							k++;
						}
						
						allimg.push(hash);
						
					}

					$(tr + "</tr>").appendTo('#tbodygeneralBanners');
					
					$('.imgTags').popover({ trigger: "hover" });		
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
			$(document).ajaxStop(function() {
				$('#searching').css("display", "none");
				
				for(x = 0; x < allimg.length; x++) {
					if($.inArray(allimg[x], selectedImages) != -1) {
						$("#tbodygeneralBanners #" + allimg[x]).closest(".imgForSelect").css('background-color', 'darkgoldenrod');
					}
				}
				
			});
		}
	});	

	function allValid() {
		if($("select[name='domainFilter']").val() == 0)
			return false;
		
		if($("select[name='platformFilter']").val() == 999)
			return false;
		else if($("select[name='platformFilter']").val() == 1 || $("select[name='platformFilter']").val() == 2) {
			if($("select[name='osFilter']").val() == 0)
				return false;
		} else if($("select[name='platformFilter']").val() == 0 && $("select[name='osFilter']").val() != 0)
			return false;
		
		if($("select[name='genreFilter']").val() == 999)
			return false;
		
		if($("select[name='countryFilter']").val() == 0)
			return false;
		
		if($("select[name='njumpFilter']").val() == 0)
			return false;
		
		if($('#ageF').multipleSelect('getSelects').length == 0)
			return false;
		
		if($("select[name='sourceFilter']").val() == 0)
			return false;
	
		if(selectedImages.length == 0) {
			alert("Select at least one banner.")
			return false;
		}
		
		if($('#titleTagedit').val() == "") {
			return false;
		}
		
		if($('#descriptionTagedit').val() == "") {
			return false;
		}
	
		return true;
	}
	
	$('#createBulkButton').on('click', function() {
	
		if(!allValid())
			alert('General Information Incomplete.');
		else {
			$(document).ajaxStart(function() {
				$('#creatingnewbulk').css("display", "inline-block");
			});
			
			var datageneralinfo = {};
			datageneralinfo['domain'] = $( "#domainF" ).val();
			datageneralinfo['platform'] = $( "#platformF" ).val();
			datageneralinfo['os'] = $( "#osF" ).val();
			datageneralinfo['age'] = $("#ageF").multipleSelect("getSelects");
			datageneralinfo['genre'] = $( "#genreF" ).val();
			datageneralinfo['banners'] = selectedImages;
			datageneralinfo['country'] = $( "#countryF" ).val();
			datageneralinfo['njump'] = $( "#njumpF" ).val();
			datageneralinfo['source'] = $( "#sourceF" ).val();
			datageneralinfo['title'] = $( "#titleTagedit" ).val();
			datageneralinfo['description'] = $( "#descriptionTagedit" ).val();

			$.post(
				'/mainstreambulk/createBulk',
				datageneralinfo,
				function(data, statusText) {
					
					
					refreshTable(data);
					alert('Create Complete');
				}
			);
			
			$( document ).ajaxError(function() {
				alert('Error');
			});
			
			$(document).ajaxStop(function() {
				$('#creatingnewbulk').css("display", "none");
			});
		}
	});	
	
	function refreshTable(data) {
		table.destroy();
	
		$("#tbodybulktable").empty();
	
		$("#tbodybulktable").html(data);
	
		table = $('#tableBulk').DataTable({
			"order": [[ 0, "desc" ]]
		});
	}
	
	//sources by domain
	$('#domainF').on('change', function() {
		getSources();
	});
	
	function getSources() {
		var values = {};
		values['domain'] = $("#domainF option:selected").val();
		
		if($("#domainF option:selected").val() == 0) {
			$('#sourceF').attr('disabled', true);
			$('#countryF').attr('disabled', true);
			$('#njumpF').attr('disabled', true);
			$("#sourceF option:selected").val(0);
			$("#countryF option:selected").val(0);
			$("#njumpF option:selected").val(0);
		} else {
			$.ajax({
				url: '/mainstreambulk/getSources',
				type: 'POST',
				data: values,
				crossDomain: true,
				async: false,
				dataType: 'text',
				success: function(data){
					
					$("#sourceF").empty();
					
					$("#sourceF").html('<option value="0">Select</option>' + data);
					
					$('#sourceF').attr('disabled', false);
					$('#countryF').attr('disabled', true);
					$('#njumpF').attr('disabled', true);
					$("#countryF option:selected").val(0);
					$("#sourceF option:selected").val(0);
					$("#njumpF option:selected").val(0);
			
			
				},
				error: function(){
					alert("Please try again.");
				}
			});
		}
	}
	
	//countries by domain
	$('#domainF').on('change', function() {
		getCountries();
	});
	
	function getCountries(){
		var values = {};
		values['domain'] = $("#domainF option:selected").val();
		
		if($("#domainF option:selected").val() == 0) {
			$('#countryF').attr('disabled', true);
			$('#njumpF').attr('disabled', true);
			$("#countryF option:selected").val(0);
			$("#njumpF option:selected").val(0);
		} else {
			$.ajax({
				url: '/mainstreambulk/getCountry',
				type: 'POST',
				data: values,
				crossDomain: true,
				async: false,
				dataType: 'text',
				success: function(data){
					
					$("#countryF").empty();
					
					$("#countryF").html('<option value="0">Select</option>' + data);
					
					$('#countryF').attr('disabled', false);
					$('#njumpF').attr('disabled', true);
					$("#countryF option:selected").val(0);
					$("#njumpF option:selected").val(0);
			
				},
				error: function(){
					alert("Please try again.");
				}
			});
		}
	}
	
	//njump by country
	$('#countryF').on('change', function() {
		getNJump();
	});
	
	function getNJump() {
		var values = {};
		values['country'] = $("#countryF option:selected").val();
		values['source'] = $("#sourceF option:selected").val();
		
		if($("#countryF option:selected").val() == 0) {
			$('#njumpF').attr('disabled', true);
			$("#njumpF option:selected").val(0);
		} else {
			$.ajax({
				url: '/mainstreambulk/getNjump',
				type: 'POST',
				data: values,
				async: false,
				crossDomain: true,
				dataType: 'text',
				success: function(data){
					
					$("#njumpF").empty();
					
					$("#njumpF").html('<option value="0">Select</option>' + data);
					
					$('#njumpF').attr('disabled', false);
					$("#njumpF option:selected").val(0);
				},
				error: function(){
					alert("Please try again.");
				}
			});
		}
	}
	
	$('#editclonebulk').attr('disabled', true);
	var bulk_id = 0;
	$('body').on('click', '.bulkImg', function() {
		$('#editclonebulk').attr('disabled', false);

		bulk_id = $(this).attr('bulk');
		var type = $(this).attr('type');
		
		if(type == 'editclone') {
			$('#domainF').find('option').removeAttr("selected");
			$('#sourceF').find('option').removeAttr("selected");
			$('#countryF').find('option').removeAttr("selected");
			$('#njumpF').find('option').removeAttr("selected");
			$('#platformF').find('option').removeAttr("selected");
			$('#osF').find('option').removeAttr("selected");
			$('#genreF').find('option').removeAttr("selected");
			$("#titleTagedit").val("");
			$("#descriptionTagedit").val("");
			
			bulkInformation(bulk_id);
		} else if (type == 'csv') {
			downloadInfo(bulk_id);
		} else if (type == 'details') {
			var bulk_title = $(this).attr('t');
			var bulk_description = $(this).attr('d');
			
			$('#titlebulk').text(bulk_title);
			$('#descriptionbulk').text(bulk_description);
		}
	
	});	

	
	function downloadInfo(bulk_id) {
		$.ajax({
			url: '/mainstreambulk/downloadZip?bulk=' + bulk_id,
			type: 'GET',
			async: true,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				console.log(data);
				
				setTimeout(function(){ location.href = data; }, 1000);
				
				
			},
			error: function(){
				alert("Please try again.");
			}
		});
		
		
		
		$(document).ajaxStop(function() {
			$("#closemodal").click();
		});
		
	}
	
	
	function bulkInformation(bulk_id) {
		$.ajax({
			url: '/mainstreambulk/getBulk?bulk_id=' + bulk_id,
			type: 'GET',
			async: false,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				
				
				json = $.parseJSON(data);
				
				$('#domainF').attr('disabled', false);
				$('#domainF').val(json[0]['domain']);
				
				getSources();
				$('#sourceF').attr('disabled', false);
				$('#sourceF').val(json[0]['source']);
				
				getCountries();
				$('#countryF').attr('disabled', false);
				$('#countryF').val(json[0]['country']);
				
				getNJump();
				$('#njumpF').attr('disabled', false);
				$('#njumpF').val(json[0]['njump']);
				
				$('#platformF').attr('disabled', false);
				$('#osF').attr('disabled', false);
				$('#genreF').attr('disabled', false);
				
				$('#platformF').val(json[0]['platform']);
				$('#osF').val(json[0]['os']);
				$('#genreF').val(json[0]['genre']);

				$("#titleTagedit").val(json[0]['title']);
				$("#descriptionTagedit").val(json[0]['description']);				
					
				var minage = json[0]['MIN_age'];
				var maxage = json[0]['MAX_age'];
				fillAge(minage, maxage);
				
				getBanners(bulk_id);
			
			},
			error: function(){
				alert("Please try again.");
			}
		});
	}
	
	function fillAge(minage, maxage) {
		var selectmultiple = [];
	
		switch(minage) {
			case '13':
				selectmultiple.push(0);
				break;
			case '18':
				selectmultiple.push(1);
				break;
			case '25':
				selectmultiple.push(2);
				break;
			case '35':
				selectmultiple.push(3);
				break;
			case '45':
				selectmultiple.push(4);
				break;
			case '55':
				selectmultiple.push(5);
				break;
		}
		
		switch(maxage) {
			case '17':
				selectmultiple.push(0);
				break;
			case '24':
				selectmultiple.push(1);
				break;
			case '34':
				selectmultiple.push(2);
				break;
			case '44':
				selectmultiple.push(3);
				break;
			case '54':
				selectmultiple.push(4);
				break;
			case '64':
				selectmultiple.push(5);
				break;
			case '0':
				selectmultiple.push(6);
				break;
		}

		$("#ageF").multipleSelect("setSelects", selectmultiple);
			
	}
	
	function getBanners(bulk_id) {
		selectedImages = [];
		
		$.ajax({
			url: '/mainstreambulk/getBanners?bulk_id=' + bulk_id,
			type: 'GET',
			async: false,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				
				$('#tbodychosedBanners').empty();
		
				json = $.parseJSON(data);
				
				numberOfImagesSelected = json.length-1;
				$("#numberImagesSelected").text(numberOfImagesSelected);
		
				for ( i = 0; i < json.length; i++ ) {
					
					var aditionalinfo = "Title: " + json[i]['title'] + "        Description: " + json[i]['description'];
					var state = json[i]['state'];
					if(state != 1) {					
						var col = '<div class="col-md-3"><div class="imgSelected"><img id="' + json[i]['hash'] + '" class="imgTags2" src="' + json[i]['url_cloudinary'] + '"></div></div>'
						
						selectedImages.push(json[i]['hash']);
						selectedImagesInfo[json[i]['hash']] = { id : json[i]['hash'], src: json[i]['url_cloudinary']};
						$(col).appendTo('#tbodychosedBanners');
					} 
				}
				
				
			},
			error: function(){
				alert("Please try again.");
			}
		});
	}
	
	$('#editclonebulk').on('click', function() {
	
		if(!allValid())
			alert('General Information Incomplete.');
		else {
			$(document).ajaxStart(function() {
				$('#editbulk').css("display", "inline-block");
			});
			
			var datageneralinfo = {};
			datageneralinfo['bulk_id'] = bulk_id;
			datageneralinfo['domain'] = $( "#domainF" ).val();
			datageneralinfo['platform'] = $( "#platformF" ).val();
			datageneralinfo['os'] = $( "#osF" ).val();
			datageneralinfo['age'] = $("#ageF").multipleSelect("getSelects");
			datageneralinfo['genre'] = $( "#genreF" ).val();
			datageneralinfo['banners'] = selectedImages;
			datageneralinfo['country'] = $( "#countryF" ).val();
			datageneralinfo['njump'] = $( "#njumpF" ).val();
			datageneralinfo['source'] = $( "#sourceF" ).val();
			datageneralinfo['title'] = $( "#titleTagedit" ).val();
			datageneralinfo['description'] = $( "#descriptionTagedit" ).val();

			$.post(
				'/mainstreambulk/editBulk',
				datageneralinfo,
				function(data, statusText) {
					refreshTable(data);
					alert('Edit Complete');
				}
			);
			
			$( document ).ajaxError(function() {
				alert('Error');
			});
			
			$(document).ajaxStop(function() {
				$('#editbulk').css("display", "none");
			});
		}
	});	

	
	$('#tbodygeneralBanners').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $('#tbodygeneralBanners').prop('scrollHeight')  && page != -1) {
            $('#searchBannersByTag').click();
        }
	})


});

