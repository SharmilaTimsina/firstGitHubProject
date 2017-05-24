{% extends "/headfooter.volt" %}
{% block title %}<title>Mainstream</title>{% endblock %}
{% block scriptimport %}    
    <script src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>

	
	<script type="text/javascript" src="/js/multiple-select.js"></script>
	<link href="/css/multiple-select.css" rel="stylesheet"/>
	
	<script src="/js/mainstream/main.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/mainstream.css" />	
{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
            <div class="row" id="rowsFloatRight">
             	
			</div>
			<div class="row">
             	<div class="col-md-12">
					<p class="titles">Current bulks</p>
					<table style="width: 100%;" id="tableBulk" class="table-striped table-bordered">
						<thead>
							<tr>
								<td>ID</td>
								<td>XLSX</td>
								<td>NJump</td>
								<td>Country</td>
								<td>Domain</td>
								<td>Genre</td>
								<td>Platform</td>
								<td>OS</td>
								<td>MIN Age</td>
								<td>MAX Age</td>
								<td># lines</td>
								<td>Date</td>
								<td>OPTN</td>
							</tr>
						<tbody id="tbodybulktable">
							<?php echo $tableBulks; ?>
						</tbody>
					</table>	
				 </div>
			</div>
			<div class="row">
             	<div class="col-md-12">
					<p class="titles">NEW / EDIT / CLONE bulk</p>
					<table>
						<tr>
							<td>
								<p class="subtitle">General Information:</p>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="domainF">Domain:</label>
									<select class="form-control selectFilter" id="domainF" name="domainFilter">
										 <option value="0"> Select</option>
										 <?php echo $domainsSelect; ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="domainF">Source:</label>
									<select disabled class="form-control selectFilter" id="sourceF" name="sourceFilter">
										 <option value="0"> Select</option>
									</select>
								</div>
							</td>
							
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="domainF">Country:</label>
									<select disabled class="form-control selectFilter" id="countryF" name="countryFilter">
										 <option value="0"> Select</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="domainF">Campaign (njump):</label>
									<select disabled class="form-control selectFilter" id="njumpF" name="njumpFilter">
										 <option value="0"> Select</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							
							<td>
								<div class="form-group">
									<label for="platformF">Platform:</label>
									<select class="form-control selectFilter" id="platformF" name="platformFilter">
										 <option value="999"> Select</option>
										 <option value="0"> Desktop</option>
										 <option value="1"> Mobile</option>
										 <option value="2"> Instagram</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="osF">OS:</label>
									<select disabled class="form-control selectFilter" id="osF" name="osFilter">
										 <option value="0"> Select</option>
										 <option value="1"> Android</option>
										 <option value="2"> IOS</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="ageF">Age:</label>
									<select class="form-control selectFilter" id="ageF" name="ageFilter" multiple="multiple">
										 <option value="0"> 13-17</option>
										 <option value="1"> 18-24</option>
										 <option value="2"> 25-34</option>
										 <option value="3"> 35-44</option>
										 <option value="4"> 45-54</option>
										 <option value="5"> 55-64</option>
										 <option value="6"> 65+</option>
											
									</select>
								</div>
							</td>
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="genreF">Genre:</label>
									<select class="form-control selectFilter" id="genreF" name="genreFilter">
										 <option value="999"> Select</option>
										 <option value="0"> M</option>
										 <option value="1"> F</option>	
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="genreF">Title:</label>
									<input class="form-control" type="text" id="titleTagedit" name="titlebanneredit" >
								</div>
							</td>
							<td>
								<div class="form-group filtersneweditBulk">
									<label for="genreF">Description:</label>
									<textarea class="form-control" type="text" id="descriptionTagedit" name="descriptionbanneredit"></textarea>
								</div>
							</td>
						
						</tr>
					</table>
				</div>
			</div>
			<div class="row">
             	<div class="col-md-12">
					<table>
							<tr>
								<td>
									<p class="subtitle"><span id="editNewBulkLabel">Banner TAG's - search all banners with:</span></p>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group">
										<label for="languageT">Language:</label>
										<select class="form-control selectFilter" id="languageT" name="languageTag">
											<option value="0"> All</option>
											<?php echo $selectboxLanguages; ?>
										</select>
									</div>
								</td>
								<td>
									<div class="form-group tagsneweditBulk">
										<label for="platformF">Category:</label>
										<select class="form-control selectFilter" id="categoryT" name="categoryTag">
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
									<td><p class="titleTables" id="title1">ALL Banners (click to select):</p></td>
		
									<td ><p id="pselectedimagesnumber"><span id="numberImagesSelected"> - </span> banners</p></td>
									<td><button id="selectAllImages" type="submit" name="submit" class="btn btn-default">SELECT ALL</button>	</td>
									<td><button id="deselectAllImages" type="submit" name="submit" class="btn btn-default">DESELECT ALL</button>	</td>
								</tr>
							</thead>
							<tbody id="tbodygeneralBanners">
								
							</tbody>
							<thead>
								<tr>
									<td><p class="titleTables" id="title2">SELECTED banners (click to delete):</p></td>
								</tr>
							</thead>
							</table>
							<div id="tbodychosedBanners">
								
							</div>
						
					</div>
				</div>
				<div id="rowBottonsFinal" class="row pull-right" >
					<div class="col-md-12">
						<button id="createBulkButton" type="submit" name="submit" class="btn btn-success"><span id="creatingnewbulk" class="glyphicon glyphicon-refresh glyphicon-refresh-animate savingEdit"></span>CREATE BULK</button>
						
						<button id="editclonebulk" type="submit" name="submit" class="btn btn-success"><span id="editbulk" class="glyphicon glyphicon-refresh glyphicon-refresh-animate savingEdit"></span>EDIT BULK</button>
					</div>
				</div>
				
				<!-- Modal -->
				<div id="modaldetails" class="modal fade" role="dialog">
				  <div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Details</h4>
					  </div>
					  <div class="modal-body">
						<p><b>TITLE:  </b><span id="titlebulk"></span></p>
						<p><b>DESCRIPTION:  </b><span id="descriptionbulk"></span></p>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
				
				
				
				<!-- Modal -->
				<div id="modaldownloading" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
				  <div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button style="display: none;" id="closemodal" type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Downloading ...</h4>
					  </div>
					</div>

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
	</style>

{% endblock %}
{% block simplescript %}
{% endblock %}