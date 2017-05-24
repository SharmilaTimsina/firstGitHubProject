{% extends "/headfooter.volt" %}
{% block title %}<title id="titleLp"></title>{% endblock %}
{% block scriptimport %}    

	<link rel="stylesheet" type="text/css" href="/css/landingmanager.css" />
	
	<script type="text/javascript" src="/js/clipboard.min.js"></script> 

	<script src="/js/landingm/viewlp.js"></script>

{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div class="container-fluid hidebefore"> 

    	<div class="row">
    		<div class="col-md-12">
    			<div style="display: -webkit-inline-box;" id="divalignicon"><p id="titlepage" class="titlesPages"></p><a target="_blank" id="hrefopen" href=""><img style="margin-bottom: -22px;" class="iconsTable" data-clipboard-text="" src="/img/njumppage/new_tab_circle_v2_ylw.svg"></a></div>
    			<div class="diveditcloneicons" id="divEditIcons">
	    			<a id="atargetedit" target="_blank" href=""><img lpid="" class="iconsTable" src="/img/njumppage/edit.svg"></a>
	    			<a id="atargetclone" target="_blank" href=""><img lpid="428" class="iconsTable" src="/img/njumppage/clone.svg"></a>
	    			<img id="deletelp" lpid="" class="iconsTable" src="/img/njumppage/trash.svg">
	    		</div>
    		</div>
    	</div>

    	<div class="row">
    		<div class="col-md-4">
    			<div class="row">
    				<p class="subtitles">General info:</p>
    			</div>
    			<div class="row">
    				<div class="col-md-5">
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Verticals</p></div>
    						<div id="verticalsgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Languages</p></div>
    						<div id="languagesgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Domains</p></div>
    						<div id="domainsgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Countries</p></div>
    						<div id="countriesgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Ethnicity</p></div>
    						<div id="ethgi" class="divbottomgeneral"></div>
    					</div>
    				</div>
    				<div class="col-md-2"></div>
    				<div class="col-md-5">
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Clients</p></div>
    						<div id="clientsgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Offers</p></div>
    						<div id="offersgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Name</p></div>
    						<div id="namegi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">Comments</p></div>
    						<div id="commentsgi" class="divbottomgeneral"></div>
    					</div>
    					<div class="row">
    						<div class="divTopgeneral"><p class="titlesFilters">URL</p></div>
    						<div id="urlgi" class="divbottomgeneral"></div>
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="col-md-1"></div>
    		<div class="col-md-3">
    			<div class="row">
    				<p style="    margin-left: 23px;" class="subtitles">Live info:</p>
    				<div class="row">
    					<div class="liveObjecttop">
	    					<p class="titlesFilters">Offers</p>
	   					</div>
	   					<div class="liveObjectbottom">
	   				
	   					</div>	
    				</div>
    				<div class="row">
    					<div class="liveObjecttop">
	    					<p class="titlesFilters">Countries</p>
	   					</div>
	   					<div class="liveObjectbottom">
	   					
	   					</div>	
    				</div>
    			</div>

    		</div>
    		<div class="col-md-1"></div>
    		<div class="col-md-3">
    			<div class="row">
    				<p class="subtitles">Preview</p>
    				<div class="row">
    					<div style="    display: -webkit-box;" class="previewObjecttop">
	    					<div id="urltoppreview"></div>
	   					</div>
	   					<div class="previewObjectbottom">
	   						<img class="imgPreview" id="imgpreviewlp" src=""></img>
	   					</div>	
    				</div>
    			</div>
    		</div>
    	</div>
    	

    </div>
    <style>
    	#urltoppreview {
    		text-overflow: ellipsis;
		    overflow: hidden;
		    width: 321px;
		    white-space: nowrap;
		    margin-left: 6px;
    	}
    	p.titlesPages {
		    font-size: 27px;
		    font-weight: 600;
		        margin-left: -10px;
		}
		.hidebefore {
		    margin-left: 100px;
		    margin-right: 100px;
		}
		p.subtitles {
		    font-size: 20px;
		    font-weight: 600;
		    margin-top: 40px;
		}
		.divTopgeneral {
			font-size: 15px;
		    background-color: #4f4f4f;
		    padding-top: 4px;
		    height: 30px;
		    color: white;
		    text-align: center;
		    width: 100%;
		    border-top-left-radius: 10px;
		    border-top-right-radius: 10px;
		}
		.divbottomgeneral {
		    font-size: 15px;
		    background-color: #cccccc;
		    padding-top: 4px;
		    min-height: 30px;
		    color: black;
		    text-align: center;
		    width: 100%;
		    border-bottom-left-radius: 10px;
		    border-bottom-right-radius: 10px;
		    margin-bottom: 30px;
		    padding: 10px;
		    word-wrap: break-word;
		}
		.liveObjecttop {
			font-size: 15px;
		    background-color: #4f4f4f;
		    padding-top: 4px;
		    height: 30px;
		    color: white;
		    text-align: center;
		    width: 90%;
		    border-top-left-radius: 10px;
		    border-top-right-radius: 10px;
		    margin-left: 36px;
		}
		.liveObjectbottom {
			font-size: 15px;
		    background-color: #cccccc;
		    padding-top: 4px;
		    height: 30px;
		    color: black;
		    text-align: center;
		    width: 90%;
		    border-bottom-left-radius: 10px;
		    border-bottom-right-radius: 10px;
		    margin-left: 36px;
		    margin-bottom: 30px;
		}
		.previewObjecttop {
		    margin-left: 1px;
		    font-size: 15px;
		    background-color: #4f4f4f;
		    padding-top: 5px;
		    height: 28px;
		    color: white;
		    text-align: center;
		    width: 361px;
		    border-top-left-radius: 10px;
		    border-top-right-radius: 10px;
		    margin-left: 10px;
		}
		.previewObjectbottom {
		    margin-left: 1px;
		    font-size: 15px;
		    padding-top: 4px;
		    width: 360px;
		    border-bottom-left-radius: 10px;
		    border-bottom-right-radius: 10px;
		    margin-left: 10px;
		}

		.imgPreview {
			border: 1px solid black;
    		margin-top: -4px;
		}
		img.iconsTable.tablecopyurlto.copyTo {
		    width: 18px;
		    padding-bottom: 4px;
		    cursor: pointer;
		}
		.hidebefore {
		    margin-left: 15px;
		    margin-right: 50px;
		}
		.titlesFilters {
		    font-weight: 900;
		    /* color: #555; */
		    font-size: 16px;
		    color: white;
		}
		#divEditIcons {
		    float: right;
		}
		img.iconsTable {
		    width: 24px;
		    cursor: pointer;
		}
		a#hrefopen {
		    margin-left: 20px;
		}
		#divEditIcons {
		    float: right;
		    margin-top: 10px;
		}
    </style>
{% endblock %}
{% block simplescript %}
{% endblock %}