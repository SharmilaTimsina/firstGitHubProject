{% extends "/headfooter.volt" %}
{% block title %}<title id="titleLp"></title>{% endblock %}
{% block scriptimport %}    

	<link rel="stylesheet" type="text/css" href="/css/landingmanager.css" />
	
	<script type="text/javascript" src="/js/clipboard.min.js"></script> 

	<link href="/css/chosen.min.css" rel="stylesheet"/>

	<script src="/js/landingm/actionslps.js"></script>

	<script src="/js/landingm/chosen.jquery.min.js"></script>

	<script type="text/javascript" src="/js/vendor/bootstrap-filestyle.min.js"></script> 

{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div class="container-fluid hidebefore"> 

    	<div class="row">
    		<p class="titlesPages">New PreLanding Page</p>
    		<div class="diveditcloneicons" id="divEditIcons">
    			<a id="atargetview" target="_blank" href=""><img class="iconsTable" src="/img/njumppage/triye2.svg"></a>
    			<a id="atargetclone" target="_blank" href=""><img lpid="428" class="iconsTable" src="/img/njumppage/clone.svg"></a>
    			<img id="deletelp" lpid="" class="iconsTable" src="/img/njumppage/trash.svg">
    		</div>
    		<div class="diveditcloneicons" id="divCloneIcons">
    			<a id="atargetview2" target="_blank" href=""><img class="iconsTable" src="/img/njumppage/triye2.svg"></a>
    			<a id="atargetedit2" target="_blank" href=""><img lpid="" class="iconsTable" src="/img/njumppage/edit.svg"></a>
    			<img id="deletelp2" lpid="" class="iconsTable" src="/img/njumppage/trash.svg">
    		</div>
    	</div>


    	<div class="row rowsFilters3">
    		General info    	
    	</div>
    	<div class="row rowsFilters2">
    		<div class="col-md-1">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Verticals*</p></div>
				<select multiple id="verticalsSb" data-placeholder="Verticals" class="chosen-select-deselect" tabindex="-1">
					<option></option>

				</select>
    		</div>
    		<div class="col-md-2">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Languages*</p></div>
				<select multiple id="languageSb" data-placeholder="Languages" class="chosen-select-deselect" tabindex="-1">
					<option></option>

				</select>
    		</div>
    		<div class="col-md-2">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Domains*</p></div>
				<select id="domainSb" data-placeholder="Domains" class="chosen-select-deselect" tabindex="-1">
					<option></option>

				</select>
    		</div>
    		<div class="col-md-2">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Countries</p></div>
				<select multiple id="countriesSb" data-placeholder="Countries" class="chosen-select-deselect2" tabindex="-1">
					<option></option>

				</select>
    		</div>
    		<div class="col-md-1">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Ethnicity</p></div>
				<select multiple id="ethSb" data-placeholder="Ethnicity" class="chosen-select-deselect" tabindex="-1">
					<option></option>

				</select>
    		</div>
    		<div class="col-md-2">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Clients</p></div>
				<select multiple id="clientsSb" data-placeholder="Clients" class="chosen-select-deselect2" tabindex="-1">
					<option></option>

				</select>
    		</div>
    		<div class="col-md-2">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Offers</p></div>
				<select multiple id="offersSb" data-placeholder="Offers (country/client)" class="chosen-select-deselect" tabindex="-1">
					<option></option>

				</select>
    		</div>
    	</div>
    	<div class="row rowsFilters4">
    		<div class="col-md-4">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Name</p></div>
				<input id="inputName"></input>
				<div style="margin-top: 19px;" class="titleFiltersDiv"><p class="titlesFilters">Files</p></div>
				<input data-disabled="true" disabled type="file"></input>
    		</div>
    		<div class="col-md-4">
    			<div class="titleFiltersDiv"><p class="titlesFilters">Comments</p></div>
				<textarea id="textareaComments"></textarea>
    		</div>
    		<div class="col-md-4">
    			<div class="titleFiltersDiv"><p class="titlesFilters">URL*<img id="copytoclip" class="iconsTable tablecopyurlto copyTo" data-clipboard-text="" src="/img/njumppage/copy.svg"><a target="_blank" id="hrefopen" href=""><img class="iconsTable2" data-clipboard-text="" src="/img/njumppage/new_tab_circle_v2_ylw.svg"></a></p></div>
    			<textarea id="inputUrl"></textarea>
    			<button id="buttonSave" type="button" name="submit" class="btn btn-success buttonsInvest">SAVE</button>
    		</div>
    	</div>
    	<script>
    		$(":file").filestyle({
				placeholder: "Coming soon",
				buttonText: "Choose ZIP",
				disabled: true
			});
    	</script>

    </div>
    <style>
    	p.titlesPages {
		    margin-left: 11px;
		    font-size: 27px;
		    font-weight: 600;
		}
		.hidebefore {
			margin-left: 50px;
    		margin-right: 50px;
    		display: none;
		}
		.row.rowsFilters {
		    margin-bottom: 30px;
		    width: 60%;
		}
		#inputUrl {
		    width: 100%;
		    height: 62px;
		    resize: none;
		    padding: 10px;
		}
		#inputName {
		    width: 100%;
		    resize: none;
		    padding: 10px;
		    background-color: #eaebeb;
		    border: 1px solid rgb(169, 169, 169);
		}
		#textareaComments {
		    width: 100%;
		    height: 124px;
		    resize: none;
		    padding: 10px;
		    background-color: #eaebeb;
		}
		button#buttonSave {
		    float: right;
		    padding: 10px;
		}

		.titlesFilters {
		    font-weight: 900;
		    color: #555;
		    font-size: 16px;
		}
		.titleFiltersDiv {
		    width: 100%;
		    height: 32px;
		    padding: 5px;
		    background-color: #313131;
		    border-top-left-radius: 7px;
		    border-top-right-radius: 7px;
		}
		.titlesFilters {
		    font-weight: 900;
		    /* color: #555; */
		    font-size: 16px;
		    color: white;
		}
		
		.row.rowsFilters2 {
		    border-bottom: 1px solid #A9A9A9;
		    padding-bottom: 23px;
		    margin-bottom: 30px;
		}
		.chosen-container-multi .chosen-choices li.search-choice {
		    position: relative;
		    margin: 3px 5px 3px 0;
		    padding: 3px 20px 3px 5px;
		    border: 0px;
		    max-width: 100%;
		    border-radius: 3px;
		    background-color: white;
		    /* background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#f4f4f4),color-stop(50%,#f0f0f0),color-stop(52%,#e8e8e8),color-stop(100%,#eee)); */
		    /* background-image: -webkit-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%); */
		    background-image: -moz-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
		    background-image: -o-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
		    background-image: none;
		    /* background-size: 100% 19px; */
		    /* background-repeat: repeat-x; */
		    /* background-clip: padding-box; */
		    box-shadow: none;
		    /* color: #333; */
		    line-height: 13px;
		    /* cursor: default; */
		}
		.chosen-container-multi .chosen-choices {
		    position: relative;
		    overflow: hidden;
		    margin: 0;
		    padding: 0 5px;
		    width: 100%;
		    height: auto;
		    border: 1px solid #eaebeb;
		    background-color: #eaebeb;
		    background-image: none;
		    background-image: none;
		    background-image: -moz-linear-gradient(#eee 1%,#fff 15%);
		    background-image: -o-linear-gradient(#eee 1%,#fff 15%);
		    background-image: red;
		    cursor: text;
		}
		
		.chosen-container-single .chosen-choices li.search-single {
		    position: relative;
		    margin: 3px 5px 3px 0;
		    padding: 3px 20px 3px 5px;
		    border: 0px;
		    max-width: 100%;
		    border-radius: 3px;
		    background-color: white;
		    /* background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#f4f4f4),color-stop(50%,#f0f0f0),color-stop(52%,#e8e8e8),color-stop(100%,#eee)); */
		    /* background-image: -webkit-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%); */
		    background-image: -moz-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
		    background-image: -o-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
		    background-image: none;
		    /* background-size: 100% 19px; */
		    /* background-repeat: repeat-x; */
		    /* background-clip: padding-box; */
		    box-shadow: none;
		    /* color: #333; */
		    line-height: 13px;
		    /* cursor: default; */
		}
		.chosen-container-single .chosen-single {
		    position: relative;
		    overflow: hidden;
		    margin: 0;
		    padding: 0 5px;
		    width: 100%;
		    height: auto;
		    border: 1px solid #eaebeb;
		    background-color: #eaebeb;
		    background-image: none;
		    background-image: none;
		    background-image: -moz-linear-gradient(#eee 1%,#fff 15%);
		    background-image: -o-linear-gradient(#eee 1%,#fff 15%);
		    background-image: red;
		    cursor: text;
		}
		.chosen-container-active.chosen-with-drop .chosen-single {
		    border: 1px solid #aaa;
		    box-shadow: 0 1px 0 #fff inset;
		    border-radius: 0px;
		}
		

		button#buttonSave {
		    float: right;
		    padding: 10px;
		    margin-top: 14px;
		}
		img#copytoclip {
		    width: 18px;
		    margin-left: 10px;
		    margin-top: -3px;
		    cursor: pointer;
		}
		#inputUrl {
		    width: 100%;
		    height: 62px;
		    resize: none;
		    padding: 10px;
		    background-color: #eaebeb;
		}
		.rowsFilters3 {
		    color: #555;
		    font-size: 17px;
		    margin-left: 0px;
		    margin-bottom: 10px;
		    font-weight: 600;
		    margin-top: 30px;
		}
		p.inputsBottom {
		    color: #555;
		    font-weight: 600;
		    font-size: 14px;
		}
		img.iconsTable2 {
		    width: 18px;
		    margin-left: 10px;
		    margin-top: -3px;
		    cursor: pointer;
		}
		img.iconsTable {
		    width: 24px;
		}
		img.iconsTable {
		    width: 24px;
		    cursor: pointer;
		}
		div#divEditIcons {
		    float: right;
		    margin-top: -35px;
		    display: none;
		}

		div#divCloneIcons {
		    float: right;
		    margin-top: -35px;
		    display: none;
		}
    </style>
    <style>
    @media (max-width: 2900px) {
	    p.titlesFilters {
	     
	        font-size: 19px;
	 
	    }
	    .chosen-container-multi .chosen-choices li.search-choice span {
	       
	        font-size: 19px;
	     
	    }
	    .chosen-container-single .chosen-single-with-deselect span {
	      
	        font-size: 19px;
	    
	    }
	    #inputName, #textareaComments, #inputUrl {
	        
	        font-size: 19px;
	       
	    }
	}
	@media (max-width: 2300px) {
	    p.titlesFilters {
	    
	        font-size: 18px;
	      
	    }
	    .chosen-container-multi .chosen-choices li.search-choice span {
	       
	        font-size: 14px;
	      
	    }
	    .chosen-container-single .chosen-single-with-deselect span {
	      
	        font-size: 14px;
	     
	    }
	    #inputName, #textareaComments, #inputUrl {
	        font-size: 14px;
	    
	    }

	}
	@media (max-width: 1900px) {
	    p.titlesFilters {

	        font-size: 15px;
	    
	    }
	    .chosen-container-multi .chosen-choices li.search-choice span {
	  
	        font-size: 14px;
	        
	    }
	    .chosen-container-single .chosen-single-with-deselect span {
	        
	        font-size: 14px;
	 
	    }
	    #inputName, #textareaComments, #inputUrl {
	     
	        font-size: 14px;
	      
	    }
	}
	@media (max-width: 1440px) {
	    p.titlesFilters {
	    
	        font-size: 13px;

	    }
	    .chosen-container-multi .chosen-choices li.search-choice span {
	     
	        font-size: 10px;
	      
	    }
	    .chosen-container-single .chosen-single-with-deselect span {
	      
	        font-size: 10px;
	     
	    }
	    #inputName, #textareaComments, #inputUrl {
	   
	        font-size: 10px;
	
	    }
	}
	@media (max-width: 1300px) {
	    p.titlesFilters {
	        
	        font-size: 10px;
	      
	    }
	    .chosen-container-multi .chosen-choices li.search-choice span {
	      
	        font-size: 9px;
	
	    }
	    .chosen-container-single .chosen-single-with-deselect span {
	   
	        font-size: 9px;
	     
	    }
	    #inputName, #textareaComments, #inputUrl {
	
	        font-size: 9px;
	     
	    }
	}

    </style>
{% endblock %}
{% block simplescript %}
{% endblock %}