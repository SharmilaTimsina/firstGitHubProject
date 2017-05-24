<?php  
	
	//username , sources e aggregators
	$iconWithSources = '';
	$session =  isset($this->session->get('auth')['name'])? $this->session->get('auth')['name'] : '';
					
	$agre = $this->session->get('auth')['aggregators'];
	$agre_explode = explode("," , $agre);
	$agre_five_to_five = "";
	
	$sour = $this->session->get('auth')['sources'];
	$sour_explode = explode("," , $sour);
	$sour_five_to_five = "";
	
	if($agre != "" && $agre != null){
		$agre_five_to_five = "Clients:\n";
		$i = 1;
		foreach ($agre_explode as $agreagator){
			if ($i % 5 == 0){
				$agre_five_to_five .= $agreagator . "," . "\n";
			} else {
				if ($agreagator === end($agre_explode))
					$agre_five_to_five .= $agreagator;
				else
					$agre_five_to_five .= $agreagator . ",";
			}
			$i++;
		}
		//$agre_five_to_five = substr($agre_five_to_five, 0, -2);
	}
	
	if($sour != "" && $sour != null){
		$sour_five_to_five = "\n\nSources:\n";
		$i = 1;
		foreach ($sour_explode as $sourceF){
			if ($i % 5 == 0){
				$sour_five_to_five .= $sourceF . "," . "\n";
			} else {
				if ($sourceF === end($agre_explode))
					$sour_five_to_five .= $sourceF;
				else
					$sour_five_to_five .= $sourceF . ",";
			}
			$i++;
		}
		$sour_five_to_five = substr($sour_five_to_five, 0, -1);
	}	
		
	$session =  isset($this->session->get('auth')['name'])? $this->session->get('auth')['name'] : '';
	
	if(($sour == "" || $sour == null) && ($agre == "" || $agre == null)) {
		$iconWithSources = '<a class="navbar-brand" href="/">
								<img src="/img/mobipium-logo.svg" alt="">
								<span class="beta blue">' . $session . '</span>
							</a>';
		
	} else { 
		$iconWithSources =  '<div id="tooltip1" title="' . $agre_five_to_five . ' ' . $sour_five_to_five . '">
								<a class="navbar-brand" href="/">
									<img src="/img/mobipium-logo.svg" alt="">
									<span class="beta blue">' . $session . '</span>
								</a>
							</div>';
	}
	
	//navbar dropdowns
	$navtype = $this->session->get('auth')['navtype'];

	$reportingDropDown = '';
	$redirectDropDown = '';
	$toolsDropDown = '';
	$mainstreamDropDown = '';
	if($navtype == 1) {
		// REPORTING
		$reportingDropDown = '<li><a href="../report">Reports</a></li>
							  <li><a href="../invest/index">Investment</a></li>
							  <li><a href="../freporting">Financial Reporting</a></li>
							  <li><a href="../sourcesapi">Daily Control</a></li>';	
							  
		// MAINSTREAM
		$mainstreamDropDown = '<li><a href="../mainstream/main">Mainstream</a></li>								
							   <li><a href="../google/index">Google Convs</a></li>
							   <li><a href="../mainstreambulk/index">Bulks</a></li>
							   <li><a href="../mainstreambulk/gallery">Gallery</a></li> ';	
							   
		// REDIRECT
		$redirectDropDown = '<li><a href="../njump">NJump (new)</a></li>
							<li><a href="../landingmanager">Landing Manager</a></li>
							<li><a href="../slink/index">NJump</a></li>
							 <li><a href="../smlink/index">MJump</a></li>';	
		
		// TOOLS					 
		$toolsDropDown = '<li><a href="../integration">Integration</a></li>
						  <li><a href="../ip/index">IPs Manager</a></li>
						  <li><a href="../autobid/index">AutoBid</a></li> 
						  <li><a href="../client">CRM Manager</a></li>
						  <li><a href="../campaignblocker">Offer Blocker</a></li>
						  <li><a href="../rates/index">Exchange Rates</a></li>
						  <li><a href="../alert/index">Alerts</a></li>
						  <li><a href="../sales">Sales</a></li>
						  <li><a href="../operation/index">Operation</a></li>';				 
	
	} else if($navtype == 2){ 
		// REPORTING
		$reportingDropDown = '<li><a href="../report/index">Reports</a></li>
							  <li><a href="../invest/index">Investment</a></li>';	
							  
		// MAINSTREAM
		$mainstreamDropDown = '<li><a href="../mainstream/main">Mainstream</a></li>								
							   <li><a href="../google/index">Google Convs</a></li>
							   <li><a href="../mainstreambulk/index">Bulks</a></li>
							   <li><a href="../mainstreambulk/gallery">Gallery</a></li> ';
							   
		// REDIRECT
		$redirectDropDown = '<li><a href="../slink/index">NJump</a></li>
							 <li><a href="../smlink/index">MJump</a></li>
							 ';
		
		// TOOLS					 
		$toolsDropDown = (($this->session->get('auth')['id'] == '21' || $this->session->get('auth')['id'] == '14'|| $this->session->get('auth')['id'] == '31') ? '<li><a href="../integration/index">Integration</a></li>' : '') .
						  '<li><a href="../ip/index">IPs Manager</a></li>
						  <li><a href="../rates/index">Exchange Rates</a></li>
						  <li><a href="../alert/index">Alerts</a></li>
						  <li><a href="../campaignblocker">Offer Blocker</a></li>
						  <li><a href="../operation/index">Operation</a></li>';	
		
	} else if($navtype == 3){ 
		// REPORTING
		$reportingDropDown = '<li><a href="../report/index">Reports</a></li>
							  <li><a href="../freporting">Financial Reporting</a></li>';
			
		// MAINSTREAM
		$mainstreamDropDown = "<style>#mainstreamDropdown {display: none;}</style>";
							   
		// REDIRECT
		$redirectDropDown = '<style>#redirectDropDown {display: none;}</style>';	
		
		// TOOLS					 
		$toolsDropDown = '<li><a href="../integration/index">Integration</a></li>
						  <li><a href="../ip/index">IPs Manager</a></li>
						  <li><a href="../client">CRM Manager</a></li>
						  ' . (($this->session->get('auth')['id'] == '8' ) ? '<li><a href="../campaignblocker">Offer Blocker</a></li>' : '') .
						  '<li><a href="../rates/index">Exchange Rates</a></li>
						  <li><a href="../sales">Sales</a></li>
						  <li><a href="../operation/index">Operation</a></li>';	
						  					  
	} else if($navtype == 4){ 
		// REPORTING
		$reportingDropDown = '<li><a href="../report/index">Reports</a></li>
							  <li><a href="../sourcesapi">Daily Control</a></li>';
							  
		// MAINSTREAM
		$mainstreamDropDown = '<style>#mainstreamDropdown {display: none;}</style>';
							   
		// REDIRECT
		$redirectDropDown = '<li><a href="../njump">NJump (new)</a></li>
							<li><a href="../landingmanager">Landing Manager</a></li>
							<li><a href="../slink/index">NJump</a></li>
							<li><a href="../smlink/index">MJump</a></li>';	
		
		// TOOLS					 
		$toolsDropDown = '<li><a href="../ip/index">IPs Manager</a></li>
						  <li><a href="../autobid/index">AutoBid</a></li>
						  <li><a href="../rates/index">Exchange Rates</a></li>
						  <li><a href="../alert/index">Alerts</a></li>
						  <li><a href="../sales">Sales</a></li>
						  <li><a href="../operation/index">Operation</a></li>';
						  
	} else if($navtype == 5){ 
		// REPORTING
		$reportingDropDown = '<li><a href="../report/index">Reports</a></li>
							  <li><a href="../freporting">Financial Reporting</a></li>';
							  
		// MAINSTREAM
		$mainstreamDropDown = '<style>#mainstreamDropdown {display: none;}</style>';
							   
		// REDIRECT
		$redirectDropDown = '<style>#redirectDropDown {display: none;}</style>';	
		
		// TOOLS					 
		$toolsDropDown = '<li><a href="../client">CRM Manager</a></li>
						  <li><a href="../integration">Integration</a></li>
						  <li><a href="../rates/index">Exchange Rates</a></li>
						  <li><a href="../sales">Sales</a></li>';

	} else if($navtype == 6){ 
		// REPORTING
		$reportingDropDown = '<style>#reportingDropDown {display: none;}</style>';
							  
		// MAINSTREAM
		$mainstreamDropDown = '<style>#mainstreamDropdown {display: none;}</style>';
							   
		// REDIRECT
		$redirectDropDown = '<style>#redirectDropDown {display: none;}</style>';	
		
		// TOOLS					 
		$toolsDropDown = '<li><a href="../rates/index">Exchange Rates</a></li>
						  <li><a href="../operation/index">Operation</a></li>';

	} else if($navtype == 7){ 
		// REPORTING
		$reportingDropDown = '<style>#reportingDropDown {display: none;}</style>';
							  
		// MAINSTREAM
		$mainstreamDropDown = '<style>#mainstreamDropdown {display: none;}</style>';
							   
		// REDIRECT
		$redirectDropDown = '<style>#redirectDropDown {display: none;}</style>';	
		
		// TOOLS					 
		$toolsDropDown = '<style>#toolsDropDown {display: none;}</style>';

	}
	
?>
{# headfooter.volt #}
{{ content() }}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta https-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/img/mobipium-favicon.png"/>
         {% block title %}{% endblock %}
        <style>
            body {
                padding-top: 100px;
                padding-bottom: 20px;
            }
        </style>

        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="/css/custom-sky-forms.css">

        <link rel="stylesheet" href="/css/main.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>

        <script src="/js/main.js"></script>
        
         <!--[if lt IE 10]>
            <script src="/plugin/sky-forms/js/jquery.placeholder.min.js"></script>
        <![endif]-->        
        <!--[if lt IE 9]>
            <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <script src="/plugin/sky-forms/js/sky-forms-ie8.js"></script>
        <![endif]-->
		<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
		<script src="/js/jquery.tooltipster.min.js"></script>
        {% block scriptimport %}
        
        
        
        {% endblock %}
    </head>
    <body>
        {% block preloader %}{% endblock %}
        
		
		
		
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
					<?php echo $iconWithSources; ?>

                </div>
				
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                    	<?php 
                    		if($navtype == 1 || $navtype == 2 || $navtype == 3 || $navtype == 4)
                    			echo '<li><a href="../offerpack/index2">Offers</a></li>';
                    	?>
						<li id="reportingDropDown" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Reporting
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?php echo $reportingDropDown; ?>
							</ul>
						</li>
						<li id="mainstreamDropdown" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Mainstream
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?php echo $mainstreamDropDown; ?>
							</ul>
						</li>
						<li id="redirectDropDown" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Redirect
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?php echo $redirectDropDown; ?>
							</ul>
						</li>
						<li id="toolsDropDown" class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Tools
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?php echo $toolsDropDown; ?>
							</ul>
						</li>
						<li><a href="../ticketsystem2">Ticket System</a></li>
						<li><a href="../session/end">Logout</a></li>
				
                    </ul>
                </div>
            </div>
        </div>
		
        {% block content %}{% endblock %}
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">Copyright &copy; 2017 Mobistein. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
        
    {% block simplescript %}{% endblock %}    
    </body>
		
	<style>
		#tooltip1 {
			opacity: 1;
		}
		
		ul.dropdown-menu {
			background-color: black;
		}
		ul.dropdown-menu > li a {
			color: white;
		}
	</style>
</html>