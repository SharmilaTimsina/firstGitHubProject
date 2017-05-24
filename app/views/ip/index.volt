{% extends "/headfooter.volt" %}
{% block title %}<title>IP Manager</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="/js/ip/main.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/ip.css" />

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
                <p class="titles">IP Manager - Global Database</p>
                <div class="col-md-12">
                    <select class="form-control" id="cc" name="cc">

                    </select><br>
                    <a disabled id="buttondownloadexcel" type="button" name="submit" class="btn btn-info">DOWNLOAD</a>


                </div>
            </div>
            <div class="row">
                <br><br><br>
            </div>
            <div >
                <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-4">
                            <form class="form-group" name="formjump" id="myform" enctype="multipart/form-data">
                                <h3>Clients Custom IPs</h3>
                                <div class="form-group"> <label for="">Files:</label><br> <input name="files[]" type="file" id="fileupload" accept=".csv"><br> </div>
                                <div class="form-group"> <select class="search-box-sel-all" id="countrycode" name="country" style="width:150px;">								<option disabled selected>Country</option>								<?php echo isset($countries) ? $countries : ''; ?></select><br> </div>
                                <div class="form-group"> <select class="search-box-sel-all" id="carriername" style="width:100px;" name="carrier">								<option></option>							</select><br> </div>  
                                <input type="submit" id="lebutton"  class="btn btn-info" value="Insert IP file">
                            </form>
                            <a id="clientipsdownload" type="button" name="submit" class="btn btn-info">Download Client IPs</a>     
                        </div>
                        <div class="col-md-4" id="divInsert">
                            <div class="row">
                                <h3>New Carrier</h3>
                                <div class="col-md-12">
                                    <select class="search-box-sel-all" id="countrycodenew" name="country" style="width:150px;">								<option disabled selected>Country</option>								<?php echo isset($countries) ? $countries : ''; ?>							</select><br><br>
                                    <input type="text" placeholder="Carrier Name"  id="newcarrier"><br><br>
                                    <a id="insertnewcarrier" type="button" name="submit" class="btn btn-info">Insert new Carrier</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" id="divInsert2">
                            <div class="row">
                                <h3>New Country and Carrier</h3>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Country Code" id="newcc"><br><br>
                                    <input type="text" placeholder="Carrier Name"  id="newccarrier"><br><br>
                                    <a id="insertnewcountrycarrier" type="button" name="submit" class="btn btn-info">Insert new Carrier</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

{% endblock %}
{% block simplescript %}<script>
    $(document).ready(function() {
		
        <?php 
            $navtype = $this->session->get('auth')['navtype'];
            if($navtype != 1 && $this->session->get('auth')['id'] != 7) {
                echo '$("#lebutton").remove();';
                echo '$("#divInsert").remove();';
                echo '$("#divInsert2").remove();';
            }
        ?>

        $("#clientipsdownload").on("click", function() {
            if ($("#countrycode").val() != null && $("#countrycode").val() != 'undefined' && $("#countrycode").val() != '' ) {
                url= '/ip/getclientips?country=' + $("#countrycode").val()+'&carrier='+$("#carriername").val();
                    
				window.open(url,'_blank');
            }
			else{
				alert("Choose a country!\n"+"The IT Team say thanks");
			}
        });
	
        $("#insertnewcountrycarrier").on("click", function() {
            if ($("#newcc").val() != null && $("#newcc").val() != 'undefined' && $("#newcc").val() != '' && $("#newccarrier").val() != 'undefined' && $("#newccarrier").val() != null && $("#newccarrier").val() != '') {
                url= '/ip/addNewCountry?country=' + $("#newcc").val()+'&carrier='+$("#newccarrier").val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    async: true,
                    success: function(data) {
                        console.log(data);
                        if (data != 0) {
                            alert(data);
                        }
                        else{
                            location.reload();
                        }
                    },
                    error: function(response) {
                        alert("error");
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
            else{
                    alert("Choose a country and the corresponding Operator!\n"+"The IT Team say thanks");
            }
        });
	
	$("#insertnewcarrier").on("click", function() {
            if ($("#countrycodenew").val() != null && $("#countrycodenew").val() != 'undefined' && $("#countrycodenew").val() != '' && $("#newcarrier").val() != 'undefined' && $("#newcarrier").val() != null && $("#newcarrier").val() != '') {
                url= '/ip/addNewCarrier?country=' + $("#countrycodenew").val()+'&carrier='+$("#newcarrier").val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    async: true,
                    success: function(data) {
                        console.log(data);
                        if (data != 0) {
                            alert(data);
                        }
                        else{
                            location.reload();
                        }
                    },
                    error: function(response) {
                        alert("error");
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
            else{
                    alert("Choose a country and the corresponding Operator!\n"+"The IT Team say thanks");
            }
        });
	
	
        $("#countrycode").on("change", function() {
            if ($("#countrycode").val() != null && $("#countrycode").val() != 'undefined' && $("#countrycode").val() != '') {
                $.ajax({
                    url: '/ip/getcarriers?country=' + $("#countrycode").val(),
                    type: 'GET',
                    async: true,
                    success: function(data) {
                        console.log(data);
                        if (data != 0) {
                            $("#carriername").empty().append(data);
                        }else{
                            location.reload();
                        }
                    },
                    error: function(response) {
                        alert("error");
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });
        $("#myform").submit(function(event) {
            event.preventDefault();
            if (true) {
                var fileInput = $('#fileupload').get(0).files[0];;
                var r = confirm("Please confirm this: \nCountry:"+$("#countrycode").val()+ " \nCARRIER:"+$("#carriername").val());
                if (r != true) {
                    return;
                }
                var formData = new FormData();
                formData.append('country', $("#countrycode").val());
                formData.append('carrier', $("#carriername").val());
                formData.append('file', fileInput);
                $("#lebutton").attr('disabled', 'disabled');
                $.ajax({
                    url: '/ip/getCustomIPs',
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function(data) {
                        console.log(data);
                        if (data == '0') {
                            alert('New IPs correctely inserted');
                        } else {
                            alert(data);
                        }
                        $("#lebutton").removeAttr('disabled');
                    },
                    error: function(response) {
                        alert("error");
                        $("#lebutton").removeAttr('disabled');
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            } else {
                alert("File not found.");
            }
        });
    });
</script>
{% endblock %}