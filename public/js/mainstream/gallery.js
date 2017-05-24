$(document).ready(function() {
	
	$(":file").filestyle({
		placeholder: "No files",
		buttonText: "Choose files"
	});
	//$('#searchBannersByTagedit').attr('disabled', true);
	
	var selectedImg = 0;
	$('body').on('click', '#tbodygeneralBanners .imgForSelect > .imgTags',  function() {
				
		if($(this).closest('.imgForSelect').css('background-color') == 'rgb(184, 134, 11)' || $(this).closest('.imgForSelect').css('background-color') == 'darkgoldenrod') {

			$(this).closest('.imgForSelect').css('background-color', 'transparent');
		
			var id = $(this).attr("id");
			selectedImg = 0;
			
			//emptyEdit();
			
		} else {
			$("#tbodygeneralBanners td div").each(function() {
				$(this).css('background-color', 'transparent');
			});
			
			$(this).closest('.imgForSelect').css('background-color', 'darkgoldenrod');
			
			var id = $(this).attr("id");
			selectedImg = id;
			
			//fillEdit(id);
			
		}
			
	});	

	function emptyEdit() {
		$("#categoryTedit option").removeAttr("selected");
		$("#languageTedit option").removeAttr("selected");
			
		$('#categoryTedit option[value=0]').attr('selected','selected');
		$('#languageTedit option[value=0]').attr('selected','selected');
		
		
		$('#searchBannersByTagedit').attr('disabled', true);
	}
	
	var hash = "";
	function fillEdit(id) {
		$("#categoryTedit option").removeAttr("selected");
		$("#languageTedit option").removeAttr("selected");
			
		var language = json[id].attributes.language;
		var category = json[id].attributes.category;
		
		hash = json[id].attributes.hash;
			
		$('#categoryTedit option[value=' + category + ']').attr('selected','selected');
		$('#languageTedit option[value=' + language + ']').attr('selected','selected');
		$('#categoryTedit').val(category);
		$('#languageTedit').val(language);
		
		
		$('#searchBannersByTagedit').attr('disabled', false);
	}
	
	
	
	var form = $("#uploadBanners");
	
	function isvalid2() {
		
		if($("input[name='files[]']").val() == "")
			return false;	
		
		
		if($("select[name='languageTag']").val() == 0)
			return false;	
		
		
		if($("select[name='categoryTag']").val() == 0)
			return false;	
		
		
		return true;
	}
	
	$(form).on("submit", function(event) {
		event.preventDefault();
		
		if (isvalid2()) {
			
			$(document).ajaxStart(function() {
				$('#savingInsert').css("display", "inline-block");
			});
			
			formData = new FormData($('#uploadBanners')[0]);
	
			$.ajax({
				url: '/mainstreambulk/insertbanners',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					if(data == 0) {
						alert('Upload Complete');
					} else {
						alert(data);
					}
					
				},
				error: function() {
					alert('Error');
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
			$(document).ajaxStop(function() {
				$('#savingInsert').css("display", "none");
			});
			
		} else {
			alert('Fill all inputs.');
		}
	});
	
	var json = "";
	var page = -1;
	languageOld = '';
	categoryOld = '';
	useridOld = '';
	last = false;
	$('#searchBannersByTag').on('click', function() {
		
		
		//if(page != -1)
		//	emptyEdit();

		var language = $("select[name='languageTagsearc']").val();
		var category = $("select[name='categoryTagsearch']").val();
		
		
		if($("select[name='languageTagsearc']").val() == 0 && $("select[name='categoryTagsearch']").val() == 0 && $("#inputSearchText").val() == '')
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
			
			if(last == true) {
				$('#searchBannersByTagedit').attr('disabled', false);
				return
			}

			//$('#tbodygeneralBanners').empty();
		
		
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
						$('#searchBannersByTagedit').attr('disabled', false);
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
						
						htmlToEditBannerID = '<span>' + id_user + '</span><br><a hash=\'' + hash + '\' id_user=\'' + id_user + '\' class=\'editidbanner\'>Edit id</a>';

						var td = '<td><div class="imgForSelect"><img data-toggle="popover" title="" data-html="true" data-placement="top" data-content="' + htmlToEditBannerID + '" data-original-title=""  id_user="' + id_user + '" id="' + hash + '" class="imgTags" src="' + url_cloudinary + '"></div></td>';	
						
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
					
					$(".imgTags").popover({ trigger: "manual" , html: true, animation:false})
					    .on("mouseenter", function () {
					        var _this = this;
					        $(this).popover("show");
					        $(".popover").on("mouseleave", function () {
					            $(_this).popover('hide');
					        });
					    }).on("mouseleave", function () {
					        var _this = this;
					        setTimeout(function () {
					            if (!$(".popover:hover").length) {
					                $(_this).popover("hide");
					            }
					        }, 300);
					});

								
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
			$(document).ajaxStop(function() {
				$('#searching').css("display", "none");
			});
		}
	});	

	$('body').on('click', '.editidbanner',  function() {

		var hash = $(this).attr("hash");
		var newId = prompt("New ID", $(this).attr("id_user"));
		if (newId != null && newId != '') {
		    
		    var formData = new FormData();
			formData.append('hash', hash);
			formData.append('newId', newId);

		    $.ajax({
				url: '/mainstreambulk/updateBannerId',
				type: 'POST',
				async: true,
				data: formData,
				success: function(data) {
					alert('Edit Complete');

					htmlToEditBannerID = '<span>' + newId + '</span><br><a hash=\'' + hash + '\' id_user=\'' + newId + '\' class=\'editidbanner\'>Edit id</a>';
					$("#" + hash).attr('data-content', htmlToEditBannerID) 
				},
				error: function() {
					alert('Error');
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}

	});

	var form = $("#editBanners");
	
	function isvalid1() {
		
		if($("select[name='languageTagedit']").val() == 0)
			return false;	
		
		
		if($("select[name='categoryTagedit']").val() == 0)
			return false;	
		
		return true;
	}
	
	$(form).on("submit", function(event) {
		event.preventDefault();
		
		if (isvalid1()) {
			
			$(document).ajaxStart(function() {
				$('#editbannerspan').css("display", "inline-block");
			});
						
			formData = new FormData($('#editBanners')[0]);
			formData.append('hash', hash);
			
			$.ajax({
				url: '/mainstreambulk/editbanner',
				type: 'POST',
				async: true,
				data: formData,
				success: function(data) {
					alert('Edit Complete');
				},
				error: function() {
					alert('Error');
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
			$(document).ajaxStop(function() {
				$('#editbannerspan').css("display", "none");
			});
			
		} else {
			alert('Fill all inputs.');
		}
	});

	$('#tbodygeneralBanners').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $('#tbodygeneralBanners').prop('scrollHeight')  && page != -1) {
            $('#searchBannersByTag').click();
        }
	})
	
});

