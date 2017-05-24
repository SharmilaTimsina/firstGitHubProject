{% extends "/headfooter.volt" %}
{% block title %}<title>Integration</title>{% endblock %}
{% block scriptimport %}
    <link rel="stylesheet" href="/css/integration.css">
    <script src="/js/invest/main.js"></script>
    <script src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>

    <script src="/js/integration.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
    <script type="text/javascript" src="/js/bootstrap-datetimepicker.js"></script>
	


{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>

{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
		
		
<ul class="nav nav-tabs">
	<?php 
		$auth = $this->session->get('auth');
		if(($auth['userlevel'] != 4) && ($auth['userlevel'] != 2) ) {
			echo '<li class="active"><a data-toggle="tab" href="#manager">Manager</a></li>';
			echo '<li><a data-toggle="tab" href="#riskiq">RiskIQ</a></li>';
		} else 
			echo '<li class="active"><a data-toggle="tab" href="#manager">Manager</a></li>';

	?>
  
 
</ul>

<div class="tab-content">
  <div id="manager" class="tab-pane fade in active">
    
	    <div class="row">
	</div>
	<div class="row">
			<?php 
				$auth = $this->session->get('auth');
				if($auth['userlevel'] != 2 )
					echo '<div class="row">';
				else 
					echo '<div style="display: none;" class="row">';
			
			?>
            
                <div class="col-md-3">
                    <table id="tableDescription">
                        <tr>
                            <td>{url}</td>
                            <td>url</td>
                        </tr>
                        <tr>
                            <td>{tid}</td>
                            <td>tracker</td>
                        </tr>
                        <tr>
                            <td>{con}</td>
                            <td>connector</td>
                        </tr>
                        <tr>
                            <td>{source}</td>
                            <td>source</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>simple replace</td>
                        </tr>
                        <tr>
                            <td>{subid}</td>
                            <td>subid</td>
                        </tr>
                    </table>


                </div>
                <div class="col-md-9">
                    <table class="table table-bordered table-hover" id="tableReportConversion">
                        <thead> <tr> <th>Client</th> <th>Year / Month</th> <th>Start day</th> <th>End day</th> <th>Duplicate</th><th>Download</th> </tr> </thead>
                        <tbody>
                        <tr>
						<form id="formConversionReport">
                            <td>
                                <select id="selectboxAgregator">
                                    <option disabled selected value> -- select an option -- </option>
                                    <?php echo $agregatorsSelect ?>
                                </select>
                            </td>
                            <td>
                                <input class="form-control" title="Period" id="convPeriod" type="text" name="convP" >
                            </td>
                            <td>
                                <input class="form-control" title="Start day" id="convPeriod2" type="text" name="convP2" >
                            </td>
                            <td>
                                <input class="form-control" title="End day" id="convPeriod3" type="text" name="convP3" >
                            </td>
                            <td>
                                <input checked type="checkbox" name="duplicate" id="duplicateCheckBox" value="dupl">Remove duplicate<br>
                            </td>
                            <td><button id="downloadReportConversions" class="btn btn-warning" type="button">DOWNLOAD</button></td>
                        </form>
						</tr>
                        </tbody>
                    </table>



                </div>
            </div>

			
            <?php 
				$auth = $this->session->get('auth');
				if($auth['userlevel'] != 2 )
					echo '<div class="row">';
				else 
					echo '<div style="display: none;" class="row">';
			
			?>
                <div class="col-md-12 tableCol12">
                    <h3 class="panel-title simple-title">Clients</h3>
                    <a href='/integration/get_excel?type=1'><img class="iconExcel" src="/img/excel.svg" id="agregatorsExcel"/></a>
                    <table id="tableAgregators" class="table-striped table-bordered tableIntegration">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Tracking Parameter
                                </th>
                                <th>
                                    Source Parameter
                                </th>
                                <th>
                                    Custom URL
                                </th>
								 <th>
                                    Currency
                                </th>
								 <th>
                                    Currency Param
                                </th>
								<th>
                                    Payout Param
                                </th>
                                <th>
                                    Edit
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tbodyAgre">
                            <?php echo $agregatorsFields ?>
                        </tbody>
                    </table>


                </div>
            </div>
				<?php 
					$auth = $this->session->get('auth');
				if($auth['userlevel'] != 3 && $auth['userlevel'] != 4)
					echo '<div class="row" id="rowSources">';
				else 
					echo '<div style="display: none;" class="row" id="rowSources">';
				
				?>
            
                <div class="col-md-12 tableCol12">
                    <h3 class="panel-title simple-title">Sources</h3>
                    <a href='/integration/get_excel?type=2'><img class="iconExcel" src="/img/excel.svg" id="sourcesExcel"/></a>
                    <table id="tableSources" class="table-striped table-bordered tableIntegration">
                        <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Name
                            </th>
							<th>
                                External Param
                            </th>
                            <th>
                                Edit
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tbodySourc">
                            <?php echo $sourceFields ?>
                        </tbody>
                    </table>


                </div>
            </div>
			
			<?php 
					$auth = $this->session->get('auth');
				if($auth['userlevel'] == 1 )
					echo '<div class="row" id="rowUsers">';
				else 
					echo '<div style="display: none;" class="row" id="rowUsers">';
				
				?>
            
                <div class="col-md-12 tableCol12">
                    <h3 class="panel-title simple-title">Users</h3>
                    
					<table id="tableUsers" class="table-striped table-bordered tableIntegration">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Countries
                            </th>
                            <th>
                                Sources
                            </th>
							 <th>
                                Edit
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tbodyUsers">
                            <?php echo $usersFields ?>
                        </tbody>
                    </table>


                </div>
            </div>

                <?php 
                    $auth = $this->session->get('auth');
                if($auth['id'] == 21 || $auth['navtype'] == 1  )
                    echo '<div class="row" id="rowUsersMainstream">';
                else 
                    echo '<div style="display: none;" class="row" id="rowUsersMainstream">';
                
                ?>
            
                <div class="col-md-12 tableCol12">
                    <h3 class="panel-title simple-title">Users Mainstream</h3>
                    <br>

                    <table id="tableUsersMainstream" class="table-striped table-bordered tableIntegration">
                        <thead>
                        <tr>
                            <th>
                                User
                            </th>
                            <th>
                                Sources
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tbodyUsers">
                            <?php echo $sourcesMainstreamUsers ?>
                        </tbody>
                    </table>
                    <style>
                        table#tableUsers {
                            margin-top: 0px;
                        }
                        #tableUsersMainstream {
                            table-layout: fixed;
                        }
                        #tableUsersMainstream {
                            word-wrap: break-word;
                            overflow-wrap: break-word;
                        }
                    </style>

                </div>
            </div>
			
			
			<?php 
					$auth = $this->session->get('auth');
				if($auth['userlevel'] == 1 || ($auth['userlevel'] == 2 && $auth['utype'] == 2))
					echo '<div class="row" id="rowDomains">';
				else 
					echo '<div style="display: none;" class="row" id="rowDomains">';
				
				?>
            
                <div class="col-md-12 tableCol12">
                    <h3 class="panel-title simple-title">Domains</h3>
                     <button id="newDomainButton" type="button" class="btn btn-warning"
                            data-toggle='modal' data-target='#newDomainModal'>
                        NEW DOMAIN
                    </button>
					<table id="tableDomains" class="table-striped table-bordered tableIntegration">
                        <thead>
                        <tr>
                            <th>
                                Domain
                            </th>
                            <th>
                                Countries
                            </th>
                            <th>
                                Sources
                            </th>
							<th>
                                Facebook PageID
                            </th>
							 <th>
                                OPTN
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tbodyDomains">
                            <?php echo $domainsFields ?>
                        </tbody>
                    </table>


                </div>
            </div>
			
			
			<?php 
					$auth = $this->session->get('auth');
				if($auth['userlevel'] == 1 )
					echo '<div class="row" id="rowMultiClick">';
				else 
					echo '<div style="display: none;" class="row" id="rowMultiClick">';
				
				?>
				<div class="col-md-12">
				<h3 class="panel-title simple-title">MultiClick Edit's</h3>
                    <table class="table table-bordered table-hover" id="tableEditClick">
                        <thead> <tr> <th>Description</th> <th>LPUrl Search</th> <th>Set edit</th> </tr> </thead>
                        <tbody>
                        <tr>
						<form id="formEditClick">
                            <td>
                                <input class="form-control" title="Description" id="editClickDescription" type="text" name="editClickDescription" >
                            </td>
                            <td>
                                <input class="form-control" title="Description" id="editClickSearch" type="text" name="editClickLPSearch" >
                            </td>
                            <td><button id="setEdit2" class="btn btn-warning" type="button"> EDIT</button></td>
                            <button id="setEdit" class="btn btn-warning" type="button" data-toggle='modal' data-target='#editMultiClick'> EDIT</button>
                        </form>
						</tr>
                        </tbody>
                    </table>



                </div>
                <div class="col-md-12 tableCol12">
                    
                     
					<table id="tableMClick" class="table-striped table-bordered tableIntegration">
                        <thead>
                        <tr>
                            <th>
                                Description
                            </th>
                            <th>
                                Search
                            </th>
                            <th>
                                Date
                            </th>
							 <th>
                                Revert
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tbodyMClick">
                            <?php echo $mClickFields ?>
                        </tbody>
                    </table>


                </div>
            </div>
			
      
	
	 

	
	
	  </div>
	  
    </div>
	  <div id="riskiq" class="tab-pane fade">
    <div class="row">
	</div>
		
	  <div class="row">
                
                <div class="col-md-12">
                    <table class="table table-bordered table-hover" id="tableRiskiq">
                        <thead> <tr> <th>URL</th> <th>Teste name</th> <th>Submit</th> </tr> </thead>
                        <tbody>
                        <tr>
						<form id="formRiskIqTest">
                            <td>
                               <input class="form-control" name="urlTestRisk" id="urlTestRisq" type="text" >
                            </td>
                            <td>
                                <input class="form-control" name="nameTestRisk" id="testNameRisq" type="text" >
                            </td>
                            <td><button id="submitTest" class="btn btn-warning" type="button">NEW TEST</button></td>
                        </form>
						</tr>
                        </tbody>
                    </table>



                </div>
            </div>
			
            <div class="row">
                <div class="col-md-12 tableCol12">
                    <h3 class="panel-title simple-title">RiskIQ Tester</h3>
                    
                    <table id="tableRisqIq" class="table-striped table-bordered tableIntegration">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    HashMask 
                                </th>
                                <th>
                                    LP Url
                                </th>
                                <th>
                                    Campaign Name
                                </th>
                                <th>
                                    Client Url
                                </th>
								 <th>
                                    Percent
                                </th>
                                <th>
                                    OPTN
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tbodyRisqIq">
                            <?php echo $risqIqFields ?>
                        </tbody>
                    </table>


                </div>


	
	
	
	
	
	
  </div>
  
</div>
	
	 <!-- Modal -->
    <div class="modal fade" id="revertMultiClick" tabindex="-1" role="dialog"
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
						Reverse MultiClick Action
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
					<p>Description: <span id="descriptionModalReverse"></span></p>
					<p>LP Search: <span id="lpsearchModalReverse"></span></p>
					<p>Date: <span id="dateModalReverse"></span></p>
					
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModal" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="confirmReverseModal" type="button" class="btn btn-primary">
                        Reverse
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>
	
	 <!-- Modal -->
    <div class="modal fade" id="editMultiClick" tabindex="-1" role="dialog"
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
						Change Clicks
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
					<p>Description: <span id="descriptionModal"></span></p>
					<p>LP Search: <span id="lpsearchModal"></span></p>
					<p style="color: red;font-weight: 900;font-size: 20px;"><span id="numberLinesAffected"></span> lines will be affected</p>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalConfirmEdit" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button disabled id="confirmEditModal" type="button" class="btn btn-primary">
                        Confirm
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editAgregator" tabindex="-1" role="dialog"
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
                        Edit Client
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="formAgregatorEdit">
                        <div class="form-group">
                            <label for="id">ID:</label>
                            <input disabled type="text" class="form-control" id="idModal" name="id"/>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="nameModal" name="name"/>
                        </div>
                        <div class="form-group">
                            <label for="trackingparameter">Tracking parameter:</label>
                            <input type="text" class="form-control" id="trackingparameterModal" name="trackingparameter"/>
                        </div>
                        <div class="form-group">
                            <label for="sinfo">Source info:</label>
                            <input type="text" class="form-control" id="sinfoModal" name="sinfo"/>
                        </div>
                        <div class="form-group">
                            <label for="customurl">Custom URL:</label>

                            <?php
                                $auth = $this->session->get('auth');
                                if($auth['userlevel'] == 1){
                                    echo '<input type="text" class="form-control" id="customurlModal" name="curl"/>';
                                }
                                else {
                                    echo '<input disabled type="text" class="form-control" id="customurlModal" name="curl"/>';
                                }
                            ?>

                        </div>
						<div class="form-group">
                            <label for="sinfo">Currency:</label>
                            <input type="text" class="form-control" id="currencyModal" name="currency"/>
                        </div>
						<div class="form-group">
                            <label for="sinfo">Currency Param:</label>
                            <input type="text" class="form-control" id="currencyParamModal" name="currencyParam"/>
                        </div>
						<div class="form-group">
                            <label for="sinfo">Payout Param:</label>
                            <input type="text" class="form-control" id="payoutParamModal" name="payoutParam"/>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModal33" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="saveAgregator" type="button" class="btn btn-primary">
                        Save changes
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editSource" tabindex="-1" role="dialog"
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
                        Edit Source
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="formSourceEdit">
                        <div class="form-group">
                            <label for="id">ID:</label>
                            <input disabled type="text" class="form-control" id="idModalSource" name="id"/>
                        </div>
						<div class="form-group">
                            <label for="id">External parameters:</label>
                            <input disabled type="text" class="form-control" id="externalParamModalSource" name="parametersExternal"/>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="nameModalSource" name="sourceName"/>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalSource" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="saveSource" type="button" class="btn btn-primary">
                        Save changes
                    </button>
                    <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsaveSource" type="button" class="btn btn-primary">

                    </button>
                </div>
            </div>
        </div>
    </div>
	
	<!-- Modal -->
    <div class="modal fade" id="editUser" tabindex="-1" role="dialog"
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
                        Edit User
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="formUserEdit">
                        <div class="form-group">
                            <label for="id">Username:</label>
                            <input disabled type="text" class="form-control" id="usernameModalUser" name="username"/>
                        </div>
                        <div class="form-group">
                            <label for="name">Countries:</label>
                            <textarea class="textareamodaluser" name="countriesUser" id="countriesModalUser"></textarea>
                        </div>
						<div class="form-group">
                            <label for="name">Sources:</label>
                            <textarea class="textareamodaluser" name="sourcesUser" id="sourcesModalUser"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalUser" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="saveUser" type="button" class="btn btn-primary">
                        Save changes
                    </button>
                    <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsaveUser" type="button" class="btn btn-primary">

                    </button>
                </div>
            </div>
        </div>
    </div>

	 <!-- Modal -->
    <div class="modal fade" id="savecomplete" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button id="close2modal" type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title">
                        SAVE COMPLETE
                    </h4>
                </div>

            </div>
        </div>
    </div>
	
	 <div class="modal fade" id="newDomainModal" tabindex="-1" role="dialog"
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
                        New Domain
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="formDomainNew">
                        <div class="form-group">
                            <label for="domain">Domain:</label>
                            <input type="text" class="form-control" id="newdomainModal" name="newdomain"/>
                        </div>
                        <div class="form-group">
                            <label for="countriesDomain">Countries:</label>
							<textarea class="textareamodaluser" name="newcountriesDomain" id="newcountriesModalDomain"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sourcesDomain">Sources:</label>
							<textarea class="textareamodaluser" name="newsourcesDomain" id="newsourcesModalDomain"></textarea>
                        </div>
						<div class="form-group">
                            <label for="sourcesDomain">Facebook PageID:</label>
							<textarea class="textareamodaluser" name="newfacebookpageDomain" id="newfacebookpageModalDomain"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalnewDomain" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="savenewDomain" type="button" class="btn btn-primary">
                        Save domain
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>
	
	 <div class="modal fade" id="editDomain" tabindex="-1" role="dialog"
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
                        Edit Domain
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="formDomainEdit">
                        <div class="form-group">
                            <label for="domain">Domain:</label>
                            <input type="text" class="form-control" id="domainModal" name="domain"/>
                        </div>
                        <div class="form-group">
                            <label for="countriesDomain">Countries:</label>
							<textarea class="textareamodaluser" name="countriesDomain" id="countriesModalDomain"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sourcesDomain">Sources:</label>
							<textarea class="textareamodaluser" name="sourcesDomain" id="sourcesModalDomain"></textarea>
                        </div>
						 <div class="form-group">
                            <label for="sourcesDomain">Facebook PageID:</label>
							<textarea class="textareamodaluser" name="facebookpageDomain" id="facebookpageModalDomain"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalDomain" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        Close
                    </button>
                    <button id="saveDomain" type="button" class="btn btn-primary">
                        Save changes
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>
	
	 <div class="modal fade" id="deleteDomain" tabindex="-1" role="dialog"
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
                        Delete Domain
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                        <div class="form-group">
                            <label for="domain">ARE YOU SURE?</label>
                        </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalDeleteDomain" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        NO
                    </button>
                    <button id="deleteDomainButton" type="button" class="btn btn-primary">
                        YES
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>
	
	
	 <div class="modal fade" id="deleteRisqIq" tabindex="-1" role="dialog"
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
                        Delete RiskIQ Test
                    </h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                        <div class="form-group">
                            <label for="domain">ARE YOU SURE?</label>
                        </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button id="closeModalDeleteRisqIq" type="button" class="btn btn-default"
                            data-dismiss="modal">
                        NO
                    </button>
                    <button id="deleteRisqIqButton" type="button" class="btn btn-primary">
                        YES
                    </button>
					 <button hidden data-toggle="modal" data-target="#savecomplete" id="openmodalsave" type="button" class="btn btn-primary">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>
	
<script>


    $(document).ready(function () {

			var month = "";
            $('#convPeriod').datetimepicker({
                viewMode: 'years',
                format: 'MM/YYYY'
            });


            $('input[name="convP2"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: false,
				format: 'MM-DD',
				startDate: '01-01'	
			});
		
			
			$('input[name="convP3"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: false,
				format: 'MM-DD',
				startDate: '01-01',
			});
			
			$("input[name='convP3']").change( function() {
				$("input[name='convP3']").val($("input[name='convP3']").val().substring(3, 5));
			});
			
			$("input[name='convP2']").change( function() {
				$("input[name='convP2']").val($("input[name='convP2']").val().substring(3, 5));
			});

        });

</script>


{% endblock %}
{% block simplescript %}
{% endblock %}