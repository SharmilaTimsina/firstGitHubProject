{% extends "/headfooter.volt" %}
	{% block scriptimport %}
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/custom-sky-forms.css">
        
        <link rel="stylesheet" href="/css/main.css">

        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <!--[if lt IE 9]>
            <link rel="stylesheet" href="o/css/sky-forms-ie8.css">
        <![endif]-->
		{% endblock %}
    {% block title %}<title>Mjumps</title>{% endblock %}
{% block content %} 
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
     

<!-- Modal -->
<div class="modal fade" id="nnamem" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Name Alteration
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="nnamef" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="nname">New Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="nname" name="nname" placeholder="New Name"/>
                    </div>
                  </div>
                </form>
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="nnameb" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>



<!--//////////////////////////////////////////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="clonem" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Clone
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="clonef" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="clonen">Clone Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="clonen" name="clonen" placeholder="Clone Name"/>
                    </div>
                  </div>
                </form>
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="cloneb" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>


<!--//////////////////////////////////////////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="delm" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Clone
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="delf" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="clonen">Warning:</label>
                   <label  class="col-sm-10 control-label"
                              for="clonen"> Are you sure you want to delete this mjump? </label>
                  </div>
                </form>
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="delb" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>




<!--//////////////////////////////////////////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="nnjm" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    New Mjump
                </h4>
                
                                <!--/////ERRRORRRRRWARNING/////-->
                    <div id="ew" class="alert alert-danger" role="alert" style="display:none;margin-top:10px">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                       
                    </div>


            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form id="nnjf" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="ngroup">Group Name</label>
                     
    
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="ngroup" name="ngroup" placeholder="Group Name"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="nlurl">Url</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="nlurl" name="nlurl" placeholder="Jump Link"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label  class="col-sm-2 control-label"
                              for="nlref">Linkref</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                        id="nlref" name="nlref" placeholder="Linkref"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"
                          for="nlname" >Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="nlname" name="nlname" placeholder="Page Name"/>
                    </div>
                  </div>
                  
                  <div class="form-group">
                     <label class="col-sm-2 control-label"
                          for="nlper" >Percentage</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="nlper" name="nlper" placeholder="Percentage"/>
                    </div>
                  </div>
                </form>           
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button id="nnjs" type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>




<!--END MODAL -->






    
    <div id="wrap">
        <div class="container">
            <div class="row">
             <div class="col-md-14"> 
                <div class="col-md-3" style="padding-top:12px">
                 
                   
                   <input id="textbox" type="text" />
                   <select class="form-control" id="gcombo" size="25"  style="width:173px; overflow: auto;"> 
                    <?php echo $group_list ?>
                   </select> 
                   
                 </div>
                 
                 <div class="col-md-8">
                     <div class="panel-heading">
                        <h3 id="ntitle" class="panel-title well">Mjump</h3>
                        <h3>
                            <button id="nnj" type="button" class="btn btn-success"  data-toggle="modal" data-target="#nnjm">New Mjump</button>
                            <button id="newn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#nnamem">Alter Name</button>
                            <button id="newc" type="button" class="btn btn-warning" data-toggle="modal" data-target="#clonem">Clone</button>
                            <button id="ndel" type="button" class="btn btn-danger"  data-toggle="modal" data-target="#delm">Delete</button>
							
							<select id="epc" <?php $auth = $this->session->get('auth'); if($auth['navtype'] != 2) {	echo 'style="display: none">';} ?>>
										<option value="0">Select period</option>
										<option value="<?php echo date("Y-m-d") ?>">Today</option>
										<option value="<?php echo date("Y-m-d", strtotime("-1 days")) ?>">Last 2 days</option>
										<option value="<?php echo date("Y-m-d", strtotime("-7 days")) ?>">Last 7 days</option>
                                        <option value="<?php echo date("Y-m-d", strtotime("-15 days")) ?>">Last 15 days</option>
									    <option value="<?php echo date("Y-m-d", strtotime("-30 days")) ?>">Last 30 days</option>
                                        </select>
                             
                        </h3>
                        </div>
                      
                        <div id="mtable" class="panel-body">
                            &nbsp;
                            
                    </div>    
                 </div>
                 
<!--                <div class="col-md-3 col-md-offset-1">
                    exform
                </div>-->
            </div>
        </div>
    </div>
    
    </div>
    <!-- // <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
    <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
    <script src="/js/vendor/bootstrap.min.js"></script>
    <script src="/plugin/sky-forms/js/jquery-ui.min.js"></script>
        
    <script src="/js/smlink/main.js"></script>
    <script type="text/javascript">  
	$(document).ready(function() {
			$(".dropdown-toggle").dropdown();
		});
	
        </script>
    {% endblock %}
{% block preloader %}
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
{% endblock %}