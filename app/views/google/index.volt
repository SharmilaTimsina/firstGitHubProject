{% extends "/headfooter.volt" %}{% block scriptimport %}
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script><link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css"></script><script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>{% endblock %}{% block title %}<title>Mobistein Reporting</title>{% endblock %}{% block preloader %}	<div id="preloader">		<div id="status">&nbsp;</div>	</div>{% endblock %}{% block content %}	
<div id="wrap">		
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="panel-heading">
					<h3 class="panel-title simple-title">Google Conversions upload File</h3>
				</div>
				<div class="panel-body">
					<form role="form" class="well" id="myform" method="POST">
						<fieldset>
							<div class="form-group">

								<label class="simple-label">Sources</label>

								<select class="form-control" id="googlesource" name="selectedCampaign" required="required">

									<?php echo isset($googlesources) ? $googlesources :'' ?>

								</select>
							</div>
							<div class="form-group">

								<label class="simple-label">Begin Date</label>

								<input id="sdate" class="form-control sdate datescurrentdate"  name="s" type="text" value="<?php echo date('Y-m-d',strtotime('-1 day'));?>">
							</div>
							<div class="form-group">

								<label class="simple-label">End Date</label>

								<input  id="edate" class="form-control edate datescurrentdate"  name="e" type="text" value="<?php echo date('Y-m-d',strtotime('-1 day'));?>">
							</div>
							<button type="submit" name="action" id="lebutton" value="excel" class="btn btn-primary">Download</button>
						</fieldset>
					</form>
				</div>
			</div>
				
			<?php if($userLevel < 2 || $userid == 21) { ?>
			
			<div class="col-md-4">
				<div class="panel-heading">
					<h3 class="panel-title simple-title">Add new Google Source</h3>
				</div>
				<div class="panel-body">
					<form role="form" class="well" id="myform2" method="POST">
						<fieldset>
							<div class="form-group">

								<label class="simple-label">Sources</label>

								<select class="form-control" id="googlesourcenotadded" name="newgoogle" required="required">

									<?php echo isset($googlesources2) ? $googlesources2 :'' ?>

								</select>
							</div>
							<button type="submit" name="action" id="lebutton" value="1" class="btn btn-primary">Set as google source</button>
						</fieldset>
					</form>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
</div>
{% endblock %}
{% block simplescript %}
<script>
$(document).ready(function () {

 $("#myform").submit(function(event) {
event.preventDefault();

if ($("#googlesource").val() != null && $("#googlesource").val() != 'undefined' && $("#googlesource").val() != '' && $("#sdate").val() != 'undefined' && $("#sdate").val() != null && $("#sdate").val() != '' && $("#edate").val() != 'undefined' && $("#edate").val() != null && $("#edate").val() != '') {

url= 'https://mobisteinreport.com/google/downloadreport?source=' + $("#googlesource").val()+'&sdate='+$("#sdate").val()+'&edate='+$("#edate").val();
window.open(url,'_blank');
}
else{
	alert("Choose the source and a correct start/end date!\n"+"The IT Team say thanks");
}
});




 $("#myform2").submit(function(event) {
		event.preventDefault();
        formData = new FormData();
        
        $.ajax({
            url: '/google/addgooglesource',
            type: 'POST',
            cache: false,
			data: $( "#myform2" ).serialize(),
            processData: false,
            success: function (result) {
                if (result== 1) {
					alert('error');
                }
				else{
					alert('success');
					window.location.reload(); 
				}
            }
        });
});


});
</script>

{% endblock %}