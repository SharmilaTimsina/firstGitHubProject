{% extends "/headfooter.volt" %}
{% block title %}<title>CRM</title>{% endblock %}
{% block scriptimport %}    
    
{% endblock %}
{% block preloader %}
    <?php date_default_timezone_set('Europe/Lisbon');?> 
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
<?php  

 $url="/client"; 

 ?>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>CRM</title>
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <!-- Multiselect CSS-->
    
    <!-- Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/css/master-of-crm.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <!--ROBOTO FONTS-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,500,700">
    <!--INDEX CONTENT-->
    <div class="container2 nopad">
        <div class="container2">

                <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Search ALL + Filters -->
                <div class="col-sm-2 col-md-3">
                    <div class="row">
                        <div class="col-md-12 nopad">
                            <input class="searchbar sb1" type="search" id='searchAll' placeholder="Search CRM">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">CLIENT</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div id="" class="selectBox">
                                <select id="client" class="selectpicker clean-select" data-live-search="true">
                                    <option value="">All</option>
                                        <?php if(isset($client)){
                                        foreach($client as $value1){ ?>
                                            <option value="<?php echo $value1['id'];?>"><?php echo $value1['client'];?></option>                        
                                        <?php 
                                        } }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">ID</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div class="selectBox">
                                <select id="id" class="selectpicker clean-select" data-live-search="true">
                                    <option value="">All</option>
                                    <?php if(isset($client)){
                                    foreach($client as $value1){ ?>
                                        <option value="<?php echo $value1['id'];?>"><?php echo $value1['id'];?></option>                        
                                    <?php 
                                    } }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">TYPE</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div class="selectBox">
                                <select class="selectpicker clean-select" id="type"  data-live-search="true" multiple data-size="5" data-selected-text-format="count>2">
                                    <option value="All"  id='typeall'>All</option>
                                    <option value="AN" class='typeoption'> AN</option>
                                    <option value="CP" class='typeoption'> CP</option>
                                    <option value="O" class='typeoption'> O</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">STATUS</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div class="selectBox">
                                <select id="status" class="selectpicker clean-select" data-live-search="true">
                                    <option value="">All</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Live">Live</option>
                                    <option value="Unfit">Unfit</option>
                                    <option value="Pause">Pause</option>
                                    <option value="Idle">Idle</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">GEOS</div>
                            </div>
                        </div>
                        <form>
                            <div class="col-xs-8 col-sm-8 col-md-8 nopad" class="multiselect">
                                <div id="optionBox" class="selectBox">
                                    <select class="selectpicker clean-select" id="geo" multiple data-size="5" data-selected-text-format="count>2" data-live-search="true">
                                        <option value="All" id='geoAll'>All</option>
                                        <?php if(isset($geo)){
                                        foreach($geo as $value1){ ?>
                                        <option value="<?php echo $value1['id'];?>"><?php echo $value1['name'];?></option>
                                        <?php 
                                        } }?>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad" >
                            <div class="stitle">
                                <div class="text-center roboto-condensed">CONTENT</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div class="selectBox">
                                <select id="content" class="selectpicker clean-select" multiple data-size="5" data-selected-text-format="count>2" data-live-search="true">
                                    <option value="All">All</option>
                                    <option value="AD">AD</option>
                                    <option value="MS">MS</option>
                                    <option value="DAT">DAT</option>
                                    <option value="APP">APP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">IO</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div class="selectBox">
                                <select id="io" class="selectpicker clean-select" data-live-search="true">
                                    <option value="">All</option>
                                    <option value="Y">Y</option>
                                    <option value="N">N</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 nopad">
                            <div class="stitle">
                                <div class="text-center roboto-condensed">AM</div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 nopad">
                            <div class="selectBox">
                                <select id="am" class="selectpicker clean-select" id="am" data-live-search="true">
                                    <option value="">All</option>
                                    <?php if(isset($am)){
                                    foreach($am as $value1){ ?>
                                        <option value="<?php echo $value1['am'];?>"><?php echo $value1['am'];?></option>                        
                                        <?php 
                                    } }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 nopad">
                            <div id="search" class="filter w-700"><button>Filter</button></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 nopad">
                            <div class="clear filter w-700"><button id="clear">Clear filters</button></div>
                        </div>
                    </div>
                </div>

                <!-- //////////////////////////////////////////////////////////////////////////////////////////////// New Client + Search + Results -->
                <div class="col-sm-8 col-md-9">
                        <div class="row">
                            <div class="col-sm-10 col-md-7 nopad"style="margin-left: 20px;">
                                <div class="col-xs-2 col-sm-1 col-md-1 nopad"><a href="/client/createClient"><button style="width:42px;" id="newC"><img id="add" src="/images/plus.svg"></div>
                                <div class="col-xs-4 col-sm-4 col-md-4 nopad" id="newC-label">New Client</button></a></div>
                                <div class="col-xs-2 col-sm-1 col-md-1 nopad"><button id="newC" class="btn copyemail" style=" width:54px; padding:0 12px;" data-clipboard-action="copy"  data-clipboard-text=''><img src="/images/clone.svg"></div><!-- <button id="newC" class="btn" data-clipboard-action="copy"  data-clipboard-text='mobipium' > -->
                                <div class="col-xs-4 col-sm-4 col-md-4 nopad" id="newC-label" style="margin-left:7px;">Copy E-mail</button></div>
                            </div>
                           
                        </div>
                        
                        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////// Results -->
                        <div class="row">
                            <h3 align="center"></h3><br><br>
                            <div class="table-responsive" style="margin-top:-40px; margin-left:30px;">
                                <table id="clientTable" class="stripe cell-border" style="font-size:12px;">
                                <thead class="head-color">
                                    <tr>
                                        <th style="border-top-left-radius: 10px;" width="10%">CLIENT</th>
                                        <th width="10%">ID</th>
                                        <th width="10%">TYPE</th>
                                        <th width="10%">STATUS</th>
                                        <th width="10%">ACCOUNT NAME</th>
                                        <th width="10%">EMAIL</th>
                                        <th width="10%">SKYPE</th>
                                        <th width="10%">GEO</th>
                                        <th width="10%">IO</th>
                                        <th width="5%">AM</th>
                                        <th style="border-top-right-radius: 10px;" width="5%">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="results">
                                </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                    
        </div>  
    </div>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Scripts -->

        <!-- Minified Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- Minified Bootstrap -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
        <!-- Multiple JavaScript -->
       
        <!-- Custom JavaScript -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
        <script src="/js/client/client.js"></script>
        <script src="https://mobisteinlp.com/adminproject/js/clipboard.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        
        <!-- Inline Javascript -->
        <script>

        var clipboard = new Clipboard('.btn');
        clipboard.on('success', function(e) {
        console.log(e);
        });

        clipboard.on('error', function(e) {
        console.log(e);
        });


        </script>
{% endblock %}
{% block simplescript %}
{% endblock %}