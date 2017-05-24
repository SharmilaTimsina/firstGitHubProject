{% extends "/headfooter.volt" %}
{% block title %}<title>Permissions</title>{% endblock %}
{% block scriptimport %}    
    <script src="http://mobisteinreport.com/js/permission/main.js"></script>
	
	<script type="text/javascript" src="http://mobisteinreport.com/js/jquery.sumoselect.min.js"></script>
	<link href="http://mobisteinreport.com/css/sumoselect.css" rel="stylesheet"/>

	<script type="text/javascript" src="http://mobisteinreport.com/js/multiselect/js/jquery.multi-select.js"></script>
	<link href="http://mobisteinreport.com/js/multiselect/css/multi-select.css" rel="stylesheet"/>

	<script type="text/javascript" src="http://mobisteinreport.com/js/multiselect/js/jquery.quicksearch.js"></script>

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
								<p class="subtitle">User information:</p>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group editable">
									<label for="usersSS">Users:</label>
									<select onchange="fireUserChange(this)" class="form-control selectFilter search-box" id="usersSS" name="usersSS">
										<option disabled="disabled" selected="selected">Select option</option>
			                        	<?php echo $users; ?>
			                        </select>
								</div>
							</td>
							<td>
								<div class="form-group editable" id="countriesSBOX" style="margin-left: 40px;">
									<label for="countriesSS">Countries:</label>
									<select multiple class="form-control selectFilter search-box-sel-all" id="countriesSS" name="countriesSS">
			                        </select>
								</div>
							</td>
							
							<td>
								<div class="form-group secondStep" style="margin-left: 111px;">
									<label for="">Aggregators:</label>
									<select class="searchMulti" id='custom-headers' multiple='multiple'>
									  	<?php echo $agregators; ?>
									</select>
									<button class="aggsAply btn btn-info" style="font-size: 10px; float: right;padding: 5px;border-radius: 0px;margin-top: 4px;margin-right: 5px;">APPLY</button>
								</div>

								
							</td>
						</tr>


						<tr>
							<td>
							</td>
							<td>
							</td>

							<td>
								<div class="form-group secondStep" style="margin-left: 115px;">
									<label for="">Sources:</label>
									<select class="searchMulti" id='keep-order2' multiple='multiple'>
									  <?php echo $sources; ?>
									</select>
									<button class="sourcesAply btn btn-info" style="font-size: 10px;float: right;padding: 5px;border-radius: 0px;margin-top: 4px;margin-right: 5px;">APPLY</button>
								</div>

								<script>
									$('.searchMulti').multiSelect({
									  selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search ...'>",
									  selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search ...'>",
									  afterInit: function(ms){
									    var that = this,
									        $selectableSearch = that.$selectableUl.prev(),
									        $selectionSearch = that.$selectionUl.prev(),
									        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
									        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

									    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
									    .on('keydown', function(e){
									      if (e.which === 40){
									        that.$selectableUl.focus();
									        return false;
									      }
									    });

									    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
									    .on('keydown', function(e){
									      if (e.which == 40){
									        that.$selectionUl.focus();
									        return false;
									      }
									    });
									  },
									  afterSelect: function(values){
									    this.qs1.cache();
									    this.qs2.cache();
									  },
									  afterDeselect: function(){
									    this.qs1.cache();
									    this.qs2.cache();
									  }
									});

								</script>
							</td>
						</tr>
            		</table>
            	</div>
            </div>
        </div>
    </div>
	<style>
		.ms-container {
		    background: none;
		}
		img.arrows {
		    width: 27px;
		    margin-left: 10px;
		}
		input.search-input {
		    width: 100%;
		    border: 1px #41a6ff solid;
		    border-radius: 1px;
		    padding-left: 10px;
		}
		.secondStep {
			display: none;
		}
		.selectFilter {
			width: 150px;
		}
		p.subtitle {
		    font-size: 20px;
		    font-weight: 600;
		}
		#countriesSS {
			display: none;
		}
		.sumo_countriesSS > .optWrapper > .options li label {
			width: 93px;
		    text-overflow: ellipsis;
		    white-space: nowrap;
		    overflow: hidden;
		    display: block;
		    cursor: pointer;
		}
		li#noresults:hover {
		    background-color: transparent;
		}
	</style>
	<script>
		window.searchSelAll = $('.search-box-sel-all').SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:'Enter here.', okCancelInMulti:false });
		window.searchSelAll = $('.search-box-sel-all2').SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:'Enter here.', okCancelInMulti:true, captionFormat: 'Click to edit' });
		window.searchSelAll = $('.search-box').SumoSelect({ csvDispCount: 3, search: true, searchText:'Enter here.' });
		$('.search-box-sel-all')[0].sumo.disable();
	</script>
{% endblock %}
{% block simplescript %}
{% endblock %}