{% extends "/headfooter.volt" %}
{% block title %}<title>Banner Manager</title>{% endblock %}
{% block scriptimport %}    

	<script src="/js/mainstream/gallery.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/mainstream.css" />	
	
	<script type="text/javascript" src="/js/vendor/bootstrap-filestyle.min.js"></script> 
	<script type="text/javascript" src="/js/vendor/jquery.validate.min.js"></script> 
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
									<p class="subtitle"><span id="editNewBulkLabel">Banner TAG's (search all banners with):</span></p>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group">
										<label for="languageT">Language:</label>
										<select class="form-control selectFilter" id="languageT" name="languageTagsearc">
											<option value="0"> All</option>
											<?php echo $selectboxLanguages; ?>
										</select>
									</div>
								</td>
								<td>
									<div class="form-group tagsneweditBulk">
										<label for="platformF">Category:</label>
										<select class="form-control selectFilter" id="categoryT" name="categoryTagsearch">
											<option value="0"> All</option>
											<?php echo $selectboxCategories; ?>
										</select>
									</div>
								</td>
								<td>
									<input id="inputSearchText" placeholder="Search by name">
								</td>
								<td>
									<div class="form-group tagsneweditBulk">
										<button id="searchBannersByTag" type="submit" name="submit" class="btn btn-success"><span id="searching" class="glyphicon glyphicon-refresh glyphicon-refresh-animate savingEdit"></span>SEARCH</button>	
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table id="tableAllBanners">
							<thead>
								<tr>
									<td><p class="titleTables" id="title1">ALL Banners:</p></td>
								</tr>
							</thead>
							<tbody id="tbodygeneralBanners">
								
							</tbody>
							</table>
							<table class="deprecated">
								
							</table>
							
							<table style="margin-top: 50px;">
								<tr>
									<td>
										<p class="subtitle"><span id="insertBannerLabel">Insert banner(s) (template: LANGUAGE_CATEGORY_IDBANNER)</span></p>
									</td>
									
								</tr>
								
								<form id="uploadBanners" enctype="multipart/form-data">
									<tr>
										<td>
											<div class="form-group">
												<label style="margin-top: 20px;" for="languageT">Files ( max-size: 1,50MB , file-format: PNG):</label>
												<input name="files[]" type="file" multiple accept=".png">
											</div>
										</td>
										
									</tr>
									<tr>
										
										<td>
											<div class="form-group tagsneweditBulk">
												<button id="insertNewBanners" type="submit" name="submit" class="btn btn-success"><span id="savingInsert" class="glyphicon glyphicon-refresh glyphicon-refresh-animate savingEdit"></span>INSERT BANNER(s)</button>	
											</div>
										</td>
									</tr>
								</form>
							</table>
					</div>
				</div>
		</div>
	</div>
	<style>
		.deprecated {
			display: none;
		}
		input#inputSearchText {
		    height: 34px;
		    margin-top: 6px;
		    margin-left: 77px;
		}
		.editidbanner {
			cursor: pointer;
		}
	</style>

{% endblock %}
{% block simplescript %}
{% endblock %}