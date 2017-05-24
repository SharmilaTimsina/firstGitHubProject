{% extends "/headfooter.volt" %}
{% block title %}<title>Sources Reports</title>{% endblock %}
{% block scriptimport %}    

	<script src="/js/datepickerjst/moment.min.js"></script>
	<script src="/js/datepickerjst/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />


	<script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
    <link href="/css/sumoselect.css" rel="stylesheet"/>

	<script src="/js/FileSaver.min.js"></script>
	
	<link href="/css/sourcesapi.css" rel="stylesheet"/>

	
	<style>
	input#periodReport {
	    width: 198px;
	}
	</style>
{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
			<div class="row">
				<div class="col-md-12">
					<table>
						<tr>
							<td>
								<div class="form-group">
									<label for="periodReport">Date:</label>
									<input class="form-control selectFilter" title="Date" id="periodReport" type="text" name="periodReport" >
								</div>
							</td>
							<td>
								<div class="form-group selectsBoxes">
									<label for="sourcesSB">Source:</label>
									 <select multiple class="form-control search-box-sel-all" id="sourcesSB" name="sourcessbid[]">
                                     	<option value="ALL">All</option>
                                     	<?php echo $sourcesList ?>
                                     	<option value="OTHERS">Others</option>
                                   	 </select>
								</div>
							</td>
							<td>
								<div class="form-group selectsBoxes">
									<label for="sourcesSB">Countries:</label>
									 <select multiple class="form-control search-box-sel-all" id="countriesSB" name="countries[]">
                                     	<option value="ALL">All</option>
                                     	<?php echo $countriesList ?>
                                   	 </select>
								</div>
							</td>
							<td>
								<div style="width: 146px;" class="form-group selectsBoxes">
									<input id="minifiedCB" type="checkbox" name="minified">Daily Control<br>	
									<input id="removeblanksCB" type="checkbox" name="blanks">Remove zeros<br>									
								</div>
							</td>
							<td>
								<div style="width: 160px; margin-top: 20px;" class="form-group selectsBoxes">
									<input type="radio" name="comma" value="0" checked> format: 1.000,00<br>
  									<input type="radio" name="comma" value="1"> format: 1,000.00<br>					 
								</div>
							</td>
							<td>
								<div class="form-group selectsBoxes">
									<button id="buttonDownloadReport" type="button" class="btn btn-success">DOWNLOAD</button>									
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					
					<table class="table">
						<thead>
							<th>Source</th>
							<th>Last record</th>
						</thead>
						<tbody>
							<?php echo $lastedits ?>
						</tbody>

    				</table>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#periodReport').daterangepicker({
				startDate: moment().subtract(2, 'days'),
				endDate: moment().subtract(0, 'days'),
				alwaysShowCalendars: true,
				maxDate: moment(),
				minDate: '2016-09-01',
				format: 'YYYY-MM-DD',
	            separator: ' to ',
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 3 Days': [moment().subtract(3, 'days'), moment().subtract(1, 'days')],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
	        });

	        //window.searchSelAll = $('.search-box-sel-all').SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:'Enter here.', okCancelInMulti:false });

	        window.searchSelAll = $('.search-box-sel-all').SumoSelect({
			        csvDispCount: 3,
			        selectAll: false,
			        search: true,
			        searchText: 'Enter here.',
			        okCancelInMulti: false
			    });

			 $('.search-box-sel-all')[1].sumo.selectItem('ALL');
			 $('.search-box-sel-all')[0].sumo.selectItem('ALL');

	        //$('select#countriesSB')[0].sumo.selectAll();

	        $('#buttonDownloadReport').on('click', function(e) {
				e.preventDefault();
				
				$("#buttonDownloadReport").attr('disabled', true);
				
				if(getSumoSelects('#sourcesSB' , 6).length == 0) {
					alert("select at least one source");
					$("#buttonDownloadReport").attr('disabled', false);
					return;
				}

				var formData = new FormData();
				formData.append('date', $("#periodReport").val());
				formData.append('sources', getSumoSelects('#sourcesSB' , 6));
				formData.append('countries', getSumoSelects('#countriesSB' , 6));
				formData.append('minified', $("#minifiedCB").is(':checked'));
				formData.append('blanks', $("#removeblanksCB").is(':checked'));
				formData.append('format', $("input[name=comma]:checked").val());
				
				$.ajax({
					url: './sourcesapi/downloadReport',
					type: 'POST',
					data: formData,
					async: true,
					success: function(data) {
						$("#buttonDownloadReport").attr('disabled', false);
						var text = data;
						var filename = moment().toString();
						var blob = new Blob([text], {type: "text/plain;charset=utf-8"});
						saveAs(blob, filename+".csv");
					},
					error: function(response) {
						alert("error");
						$("#buttonDownloadReport").attr('disabled', false);
					},
					cache: false,
					contentType: false,
					processData: false
				});
			});
		});

	</script>
	<script>
	
    $(document).ready(function() {
    $( '.search-box-sel-all' ).unbind();
    $(".opt").on('click', function(e) {
        console.log("ok");

        var string = $(this).find('label').text();
        if(string.indexOf('All') !== -1) {
                	
	        if($(this).hasClass('selected')) {
	        	$(this).closest('.SumoSelect').find('.options li').removeClass('selected');
	        	$(this).closest('.SumoSelect').find('select option:selected').removeAttr("selected");
	        	$(this).closest('.SumoSelect').find('.options li:eq(0)').addClass('selected');
	        	$(this).closest('.SumoSelect').find('select option:eq(0)').prop("selected", true)
	        } else {
	        	$(this).closest('.SumoSelect').find('.options li').removeClass('selected')
	        	$(this).closest('.SumoSelect').find('select option:selected').removeAttr("selected");
	        }
	        
   	 	} else {
	        $(this).closest('.SumoSelect').find('select option:eq(0)').removeAttr("selected");
	        $(this).closest('.SumoSelect').find('.options li:eq(0)').removeClass('selected')
   	 	}

   	 	$($(this).closest('.SumoSelect').find('select'))[0].sumo.setText();
    });
    });
	</script>

{% endblock %}
{% block simplescript %}
{% endblock %}

