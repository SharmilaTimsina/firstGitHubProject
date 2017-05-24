{% extends "/headfooter.volt" %}
{% block title %}<title>Offers</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="http://mobisteinreport.com/js/offerspacks/offerpackedit.js"></script>

	<script type="text/javascript" src="http://mobisteinreport.com/js/jquery.sumoselect.min.js"></script>
	<link href="http://mobisteinreport.com/css/sumoselect.css" rel="stylesheet"/>
	

	<script type="text/javascript" src="http://mobisteinreport.com/js/vendor/bootstrap-filestyle.min.js"></script> 


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
								<div class="form-group formgroupinvest">
									<label for="statusSB">Country*:</label>
									<select class="search-box-sel-all" id="countrySB"></select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="statusSB">Status:</label>
									<select class="search-box-sel-all" id="statusSB"></select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="carrierSB">Carrier*:</label>
									<select disabled class="search-box-sel-all" id="carrierSB"></select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="modelSB">Model:</label>
									<select class="search-box-sel-all" id="modelSB"></select>
								</div>
							</td>
						
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="aggsSB">Clients*:</label>
									<select class="search-box-sel-all" id="aggsSB"></select>
								</div>
								<div id="infoaggregator"></div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="areaSB">Area*:</label>
									<select class="search-box-sel-all" id="areaSB"></select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="offname">Offer name*:</label>
									<input type="text" class="form-control" id="offname">
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="verticalSB">Vertical:</label>
									<select class="search-box-sel-all" id="verticalSB"></select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="jumpurl">Ownership:</label>
									<select class="search-box-sel-all" id="ownerSB"></select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="flowSB">Flow:</label>
									<select class="search-box-sel-all" id="flowSB"></select>
								</div>
							</td>						
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="jumpurl">Client URL*:</label>
									<textarea rows="5" class="form-control" id="jumpurl"></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest4">
									<label for="cpa">CPA*:</label>
									<input step="0.1" type="number" class="form-control" id="cpa">
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest3">
									<label for="daylicap">Daily CAP:</label>
									<input step="1" type="number" class="form-control" id="daylicap">
								</div>
							</td>
						</tr>
						
						<tr>
							<td>
								<div class="form-group formgroupinvest´2">
									<label for="currencySB">Currency Type*:</label>
									<select class="search-box-sel-all" id="currencySB"></select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="carrierSB">Exclusive:</label><br>
									<input checked type="radio" name="exclusive" value="0"> Not exclusive<br>
								    <input type="radio" name="exclusive" value="1"> Exclusive<br>
								    <input type="radio" name="exclusive" value="2"> Exclusive Google
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="accountSB">AM:</label>
									<select class="search-box-sel-all" id="accountSB"></select>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="cmSB">CM:</label>
									<select class="search-box-sel-all" id="cmSB"></select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="">Screenshot:</label>
									<div id="drop-zone">
									    Drop files or click here..
									    <input multiple id="screenshot" type="file" name="replyfiles" />
									    <div id="clickHere">
									        
									        
									    </div>
									</div>
									<div class="filesLists" id="filelist"></div>
									<div id="infoScreenshot">
										<a target="_blank" id="deleteScreenshotURL" href="<?php echo (isset($deleteScreenshot)) ? $deleteScreenshot : '' ?>	">
											<img style="cursor: pointer;" title="Delete" class="icontable" src="http://mobisteinreport.com/img/iconDelete.png">
										</a> 
										<a  target="_blank" href="<?php echo (isset($downloadScreenshot)) ? $downloadScreenshot : '' ?>" class="downloadBanners">download screenshot</a>
									</div>
								</div>
							</td>
							<td>
								<div class="form-group" style="margin-left: 163px; margin-top: -23px;">
									<label for="">Banners:</label>
									<div id="drop-zone2">
									    Drop files here or click here..
									    <input multiple id="bannersZip" type="file" name="replyfiles2" />
									    <div id="clickHere2">
									        
									    </div>
									</div>
									<div class="filesLists" id="filelist2"></div>
									<div id="infoBanners">
										<a target="_blank" id="deleteBannersURL"  href="<?php echo (isset($deleteBanners)) ? $deleteBanners : '' ?>">
											<img style="cursor: pointer;" title="Disable" class="icontable" src="http://mobisteinreport.com/img/iconDelete.png">
										</a> 
										<a href="<?php echo (isset($downloadBanners)) ? $downloadBanners : '' ?>" class="downloadBanners">download banners</a>
									</div>
								</div>
							</td>
						</tr>
						
						

					</table>
					<table>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="description">Comments:</label>
									<textarea type="text" class="form-control" id="description"></textarea>
								</div>
							</td>
							<td>
								<div class="form-group formgroupinvest2">
									<label for="statusSB">Accepted traffic and regulations:</label>
									<form id="formRegulations">
										<table>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="1">Adult</td>
												<td><input checked type="checkbox" name="regulations[]" value="2">Mainstream</td>
											</tr>
												<td><input checked type="checkbox" name="regulations[]" value="3">Desktop</td>
												<td><input checked type="checkbox" name="regulations[]" value="4">Mobile App</td>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="5">Descovery App</td>
												<td><input checked type="checkbox" name="regulations[]" value="6">Mobile Web</td>
											</tr>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="7">Email</td>
												<td><input checked type="checkbox" name="regulations[]" value="8">Push Notification</td>
											</tr>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="9">Redirects</td>
												<td><input checked type="checkbox" name="regulations[]" value="10">Popunders</td>
											</tr>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="11">Incentivized</td>
												<td><input checked type="checkbox" name="regulations[]" value="12">SMS</td>
											</tr>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="13">Misleading</td>
												<td><input checked type="checkbox" name="regulations[]" value="14">Social Networks / Facebook</td>
											</tr>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="15">IOS</td>
												<td><input checked type="checkbox" name="regulations[]" value="16">Android</td>
											</tr>
											<tr>
												<td><input checked type="checkbox" name="regulations[]" value="17">Wifi</td>
											</tr>
										</table>
									</form>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button id="buttonsaveOfferPack" type="button" name="submit" class="btn btn-success buttonsInvest">SAVE</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		var jsonVar = <?php echo (isset($offervar)) ? $offervar : "'';"; ?>		
		var clone = <?php echo $clone ?>		
	</script>
	<style>
	.form-group.formgroupinvest2 {
	    margin-left: 158px;
	    width: 400px;
	}
	.form-group.formgroupinvest3 {
	    margin-left: 158px;
	    width: 100px;
	}
	.form-group.formgroupinvest4 {
	    width: 100px;
	}
	textarea#description {
	    width: 500px;
	    height: 200px;
	    resize: none;
	}
	input[type="checkbox"] {
	    margin: 5px;
	}
	.downloadBanners {
		cursor: pointer;
		margin-left: 15px;
	}
	</style>

	<style>	
		#drop-zone {
		    width: 300px;
		    height: 200px;
		    position: absolute;
		    /* left: 50%; */
		    /* top: 100px; */
		    /* margin-left: -150px; */
		    border: 2px dashed rgba(0,0,0,.3);
		    border-radius: 20px;
		    font-family: Arial;
		    text-align: center;
		    position: relative;
		    line-height: 180px;
		    font-size: 20px;
		    color: rgba(0,0,0,.3);
		}

		    #drop-zone input {
			    /* position: absolute; */
			    cursor: pointer;
			    /* left: 0px; */
			    top: 0px;
			    opacity: 0;
			    /* padding: 98px 5px 75px 99px; */
			    margin-top: -184px;
			    margin-left: -2px;
			    width: 300px;
			    height: 205px;
			}

		    /*Important*/
		    #drop-zone.mouse-over {
		        border: 2px dashed rgba(0,0,0,.5);
		        color: rgba(0,0,0,.5);
		    }


		/*If you dont want the button*/
		#clickHere {
		    position: absolute;
		    cursor: pointer;
		    left: 50%;
		    top: 50%;
		    margin-left: -50px;
		    margin-top: 20px;
		    line-height: 26px;
		    color: white;
		    font-size: 12px;
		    width: 100px;
		    height: 26px;
		    border-radius: 4px;
		    background-color: #3b85c3;
		    display: none;

		}

	    #clickHere:hover {
	        background-color: #4499DD;

	    }
	    div#filelist {
		    border: 1px solid blue;
		    padding: 5px;
		    margin-top: 12px;
		    width: 200px;
		    margin-left: 54px;
		}
		.filename {
		    cursor: pointer;
		}
		.filename:hover {
		    cursor: pointer;
		    color: red;
		}

		.info {
		    color: blue;
		    font-weight: 700;
		}



		#drop-zone2 {
		    width: 300px;
		    height: 200px;
		    position: absolute;
		    /* left: 50%; */
		    /* top: 100px; */
		    /* margin-left: -150px; */
		    border: 2px dashed rgba(0,0,0,.3);
		    border-radius: 20px;
		    font-family: Arial;
		    text-align: center;
		    position: relative;
		    line-height: 180px;
		    font-size: 20px;
		    color: rgba(0,0,0,.3);
		}

		     #drop-zone2 input {
			    /* position: absolute; */
			    cursor: pointer;
			    /* left: 0px; */
			    top: 0px;
			    opacity: 0;
			    /* padding: 98px 5px 75px 99px; */
			    margin-top: -184px;
			    margin-left: -2px;
			    width: 300px;
			    height: 205px;
			}

		    /*Important*/
		    #drop-zone2.mouse-over {
		        border: 2px dashed rgba(0,0,0,.5);
		        color: rgba(0,0,0,.5);
		    }


		/*If you dont want the button*/
		#clickHere2 {
		    position: absolute;
		    cursor: pointer;
		    left: 50%;
		    top: 50%;
		    margin-left: -50px;
		    margin-top: 20px;
		    line-height: 26px;
		    color: white;
		    font-size: 12px;
		    width: 100px;
		    height: 26px;
		    border-radius: 4px;
		    background-color: #3b85c3;
		    display: none;

		}
	    #clickHere2:hover {
	        background-color: #4499DD;

	    }
	    div#filelist2 {
		    border: 1px solid blue;
		    padding: 5px;
		    margin-top: 12px;
		    width: 200px;
		    margin-left: 54px;
		}

	</style>
	


	
{% endblock %}
{% block simplescript %}
{% endblock %}