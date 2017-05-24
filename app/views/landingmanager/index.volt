{% extends "/headfooter.volt" %}
{% block title %}<title>PreLanding Manager</title>{% endblock %}
{% block scriptimport %}    

	<link rel="stylesheet" type="text/css" href="/css/landingmanager.css" />
	
	<script type="text/javascript" src="/js/clipboard.min.js"></script> 

	<link href="/css/chosen.min.css" rel="stylesheet"/>

	<script src="/js/landingm/index.js"></script>

	<script src="/js/landingm/chosen.jquery.min.js"></script>

	<script src="https://cdn.datatables.net/v/bs/dt-1.10.13/datatables.min.js"></script>

{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div class="container-fluid hidebefore"> 

    	<div class="row">
    		<p class="titlesPages">PreLanding Page Manager</p>
    	</div>
    	<div class="row">
    		<span id="spanNjump"><span class="labelnew">Add NEW</span><a href="/landingmanager/newlp"><img id="newlp" src="/img/njumppage/pluslanding.svg"></a></span>
    	</div>
    	<div class="row rowfilters">
    		<div class="col-md-1">  					
    			<p class="titlesFilters">Verticals</p>  					
    			<select multiple id="verticalsSb" data-placeholder="Verticals" class="chosen-select-deselect" tabindex="-1">  						
    				<option></option>					
    			</select>  				
    		</div>
    		<div class="col-md-1">  					
    			<p class="titlesFilters">Languages</p>  					
				<select multiple id="languageSb" data-placeholder="Languages" class="chosen-select-deselect" tabindex="-1">  						
					<option></option>					
				</select>			
    		</div>
    		<div class="col-md-2">  					
    			<p class="titlesFilters">Domains</p>  					
    			<select multiple id="domainSb" data-placeholder="Domains" class="chosen-select-deselect" tabindex="-1">  						
    				<option></option>					
    			</select>  				
    		</div>
    		<div class="col-md-1">  					
    			<p class="titlesFilters">Countries</p>  					
    			<select multiple id="countriesSb" data-placeholder="Countries" class="chosen-select-deselect2" tabindex="-1">  						
    				<option></option>					
    			</select>  				
    		</div>
    		<div class="col-md-1">  					
    			<p class="titlesFilters">Ethnicity</p>  					
    			<select multiple id="ethSb" data-placeholder="Ethnicity" class="chosen-select-deselect" tabindex="-1">  						
    				<option></option>					
    			</select> 				
    		</div>
    		<div class="col-md-1">  					
    			<p class="titlesFilters">Clients</p>  					
    			<select multiple id="clientsSb" data-placeholder="Clients" class="chosen-select-deselect2" tabindex="-1">  						
    				<option></option>					
    			</select>   				
    		</div>
    		<div class="col-md-2">  					
    			<p class="titlesFilters">Offers</p>  					
    			<select multiple id="offersSb" data-placeholder="Offers (country/client)" class="chosen-select-deselect" tabindex="-1">  						
    				<option></option>					
    			</select>  				
    		</div>
    		<div class="col-md-1">  					
    			<p class="titlesFilters">ID</p>  					
    			<input placeholder="ID" id="idInputFilter" class="inputIdNameFilter"> 				
    		</div>
    		<div class="col-md-1">  					
    			<p class="titlesFilters">Name</p>  					
    			<input placeholder="Name" id="nameInputFilter" class="inputIdNameFilter"> 				
    		</div>
    		<div class="col-md-1">  
    			<div class="space"></div> 					
    			<div style="display: flex;">
    			<button id="buttonFilter" type="button" name="submit" class="btn btn-success buttonsInvest">FILTER</button>  		
    			<button id="buttonClearFilter" type="button" name="submit" class="btn btn-success buttonsInvest">CLEAR</button> 
    			</div> 		
    		</div>
    	</div>
    	<div class="row" style="margin-top: 50px;">
    		<table style="width: 100%;" id="tableLps" class="table-striped table-bordered">                
    			<thead>                  
    				<tr>                    
    					<th><div>ID</div></th>                    
    					<th><div>Date</div></th>                    
    					<th><div>Verticals</div></th>                    
    					<th><div>Languages</div></th>                    
    					<th><div>Ethnicity</div></th>                    
    					<th><div>Countries</div></th>                    
    					<th><div>Name</div></th>                      
    					<th><div>URL</div></th>                    
    					<th><div>Options</div></th>                  
    				</tr>                
    			</thead>                
    			<tbody id="tbodyLps">                

    			</tbody>              
    		</table>            
    	</div>






    	

    </div>

    <style>    
    .hidebefore {
	    /* margin-left: 50px; */
	    margin-right: 50px;
	    display: none;
	}
    #tableLps {      width: 100%;    }    #tbodyLps td {        padding: 5px;    }    #tableLps th {        padding: 5px;        background-color: #4f4f4f;        color: white;    }    img.iconsTable {        width: 20px;        margin: 2px;        cursor: pointer;    }     img#newlp {        width: 49px;        margin-bottom: 37px;        margin-left: 47px;        cursor: pointer;    }    span.labelnew {        font-size: 20px;        margin-left: -58px;        position: absolute;        margin-top: 10px;        font-weight: 700;    }    span#spanNjump {        float: right;           }    div#tableLps_filter {        float: right;        margin-right: 86px;    }    .pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {        z-index: 2;        color: #fff;        background-color: #4f4f4f;        border-color: #4f4f4f;        cursor: default;    }    .pagination>li>a:hover, .pagination>li>span:hover, .pagination>li>a:focus, .pagination>li>span:focus {        color: #4f4f4f;        background-color: #eee;        border-color: #ddd;    }    .pagination>li>a, .pagination>li>span {        position: relative;        float: left;        padding: 6px 12px;        line-height: 1.42857143;        text-decoration: none;        color: #4f4f4f;        background-color: #fff;        border: 1px solid #ddd;        margin-left: -1px;    }    .hidebefore {      display: none;    }    .table-striped>tbody>tr:nth-child(even) {        background: #EAEBEB;    }    .divView .popover.bottom .arrow {        margin-left: -11px;    }    .divView .popover .popover-content {        width: 392px;        max-width: 100%;        margin-left: -186px;    }    .iconstd {       text-align: center;        display: -webkit-inline-box;        border: 0px;        margin-left: 5px;        margin-top: 8px;    }    </style>
    <style>  					.rowsFilters {  						margin-bottom: 20px;  						margin-left: 0px;  					}  					.inputIdNameFilter {					    width: 161px;					    padding-left: 5px;					}  				button#buttonFilter {              background-color: #4cae4c;              border-color: #4cae4c;              margin-left: -15px;          }					button#buttonClearFilter {					    background-color: #EA4535;					    margin-left: -5px;					    border-color: #EA4535;					}  				</style> 
    <style>
    	.space {
		    height: 22px;
		}
    	.inputIdNameFilter {
    		width: 100%;
    	}
    	p.titlesPages {
		    margin-left: 11px;
		    font-size: 27px;
		    font-weight: 600;
		}
		.row.rowsFilters {
		    margin-bottom: 30px;
		    width: 60%;
		}
		#inputUrl {
		    width: 100%;
		    height: 100px;
		    resize: none;
		    padding: 10px;
		}
		#inputName {
			width: 100%;
		    height: 100px;
		    resize: none;
		    padding: 10px;
		}
		#textareaComments {
			width: 100%;
		    height: 200px;
		    resize: none;
		    padding: 10px;
		}
		button#buttonSave {
		    float: right;
		    padding: 10px;
		}
		button#buttonClearFilter {
		    margin-left: 10px;
		}
		div#tableLps_wrapper {
		    margin-left: 13px;
		}
		#tableLps {
		    width: 100%;
		    max-width: 100%;
		}
		table#tableLps tr td {
		    padding: 5px;
		    word-break: break-all;
		    width: 107px;
		}
		div#tableLps_filter {
		    float: right;
		    margin-right: 0%;
		}
		#tbodyLps .odd tr td:nth-last-child(2) {
		    width: 600px;
		}
		img.iconsTable.tablecopyurlto {
            float: right;
        }
        .titlesFilters {
            font-weight: 900;
            color: #555;
            font-size: 16px;
        }
        table.dataTable thead .sorting { background: url('/img/iconssort/sort_both.png') no-repeat center right; }
        table.dataTable thead .sorting_asc { background: url('/img/iconssort/sort_asc.png') no-repeat center right; }
        table.dataTable thead .sorting_desc { background: url('/img/iconssort/sort_desc.png') no-repeat center right; }

        table.dataTable thead .sorting {
            background-size: 23px;
            background-position-y: 2px;
        }

        table.dataTable thead .sorting_asc {
            background-size: 23px;
            background-position-y: 9px;
        }

        table.dataTable thead .sorting_desc {
            background-size: 23px;
            background-position-y: -3px;
        }
        button#buttonFilter {
            background-color: #0BAA4B;
            border-color: #0BAA4B;
            margin-left: -15px;
        }
    </style>
{% endblock %}
{% block simplescript %}
{% endblock %}