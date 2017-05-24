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
<?php  $url="/client"; 
 $uid=25; //get the session user id from here 
 //get the anna id 
 $anna_id=8;
 
 
 $idToSend=-1;
 if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) ){
	$idToSend = $this->request->get('id');
 }
  ?>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <link href="/css/master-of-crm.css" rel="stylesheet" type="text/css">
    <link href="/css/createClient_chat.css" rel="stylesheet" type="text/css">
    <title>Create Client</title>
<?php if(isset($client)){
    foreach($client as $value){
    $result='';
	
	$current_user_id=$uid;//get current_user_id
	$value11=true;
	if($value['private_profile']==1){
		if($anna_id!=$current_user_id){
			$value11=false;
		}	
	}
?>

<div class="container-client">
<div class="alert alert-success" style="display:none">
  <strong>Success!</strong> Client Updated Successfully.
</div>
<div class="alert alert-danger" style="display:none">
  <strong>Error!</strong> Failed To Update Client. Check If Company Name Already Exist.
</div>

      <!--UPLOAD FILE-->
<form enctype="multipart/form-data" id="form2" name="form2">
    <div class="col-md-12 padd-0">
        <div class="id-container col-md-12">
        <div class="marg-b col-md-4"><b>ID&nbsp;&nbsp;</b><?php if($value['id']!=NULL OR $value['id']!=''){ echo '&nbsp;&nbsp; :'.$value['id'];} ?></div>
        <div class="marg-b col-md-4"><?php if($value['am']!=NULL OR $value['am']!=''){ echo '<b>AM&nbsp;&nbsp;&nbsp;&nbsp;</b> :'.$value['am'];} ?></div>
        <div class="marg-b col-md-4"><b>IO&nbsp;&nbsp;</b><?php if($value['io']!=NULL OR $value['io']!=''){ echo '&nbsp;&nbsp;:Y';}else{echo '&nbsp;&nbsp;:N';} ?></div>
        <input id="io[]" multiple="multiple" name="io[]" type="file" class="col-md-6">
        <div class="marg-t col-md-6">
            <div class="marg-l inline-block">Private Profile</div>
				<?php if($value11==true){ ?>
					  <input type="checkbox" name="private_profile" value="1" class="float-left" <?php if($value['private_profile']==1){ echo "checked";} ?> >
				<?php }
				else{?>
					  <input type="checkbox" name="private_profile" value="1" class="float-left" <?php if($value['private_profile']==1){ echo "checked";}  ?> disabled>
					  <input type="hidden" name="private_profile" value="1" id="private_profile">
				<?php }?>
          
        </div>
     </div>

            <!-- GRAPHIC CONTAINER --> 
			 <div class="graphic-container">
                <div class="col-md-4" style="padding-left: 0;">
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>
                <div class="col-md-4 line-h-48 months" style="padding-left: 0;">
                    <p>Agosto</p>
                    <p>Setembro</p>
                    <p>Outubro</p>
                    <p>Novembro</p>
                </div>
                <div class="col-md-4 line-h-48 offers">
                    <p>Random name</p>
                    <p>Random name</p>
                    <p>Random name</p>
                    <p>Random name</p>
                </div>
            </div>      
                  
      </div><br><br>
      <!--PRIVATE PROFILE CHECKBOX-->
    
    <div class="row col-md-6 marg-l-0">
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Client Information -->
            <div>
                <!--AM-->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">
                                AM</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <select class="form-control filter-option" id="am" name="am">
                            <option disabled selected value="">Select AM
                            </option>
                            </option>
                            <?php if(isset($am)){
							foreach($am as $value1){ ?>
							<option value="<?php echo $value1['am'];?>" <?php if($value1['am']==$value['am']){ echo "selected"; } ?> ><?php echo $value1['am'];?></option>						
							<?php 
							} }?>
                        </select>
                    </div>
                </div>
                <!--STATUS-->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">
                                Status
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <select class="form-control filter-option" id="status" name="status">
                            <option disabled selected value="">
							Status
							</option>
                           <option value="Lead" <?php if($value['status']=='Lead'){ echo "selected";} ?>>Lead</option>
						   <option value="Ongoing" <?php if($value['status']=='Ongoing'){ echo "selected";} ?> >Ongoing</option>
						   <option value="Live" <?php if($value['status']=='Live'){ echo "selected";} ?> >Live</option>
						   <option value="Unfit" <?php if($value['status']=='Unfit'){ echo "selected";} ?> >Unfit</option>
							<option value="Paused" <?php if($value['status']=='Paused'){ echo "selected";} ?> >Paused</option>
							<option value="Idle" <?php if($value['status']=='Idle'){ echo "selected";} ?> >Idle</option>	
                        </select>
                    </div>
                </div>
            </div>      
            <div class="col-md-6">
                <div class="col-md-3 nopad">
                    <div class="ititle">
                        <div class="text-center">Type</div>
                    </div>
                </div>
                    <div class="col-md-9 nopad-r">
                       <?php
					if($value['type']!=NULL || $value['type']!='')
					{	
						$array=explode(',',$value['type']);
					}
					?>
                        <select class="form-control selectpicker" multiple data-size="5" data-selected-text-format="count>2" data-live-search="true" name="type[]" id="type[]">
                           <!-- <option value="AN,CP,O" >All</option>-->
						   <!--<option value="All">All</option>-->
							
                            <option value="AN" 
							<?php
							if($value['type']!=NULL || $value['type']!='')
							{
								foreach($array as $value2)
								{
									if($value2=='AN'){
										echo 'selected';
										break;
									}
								}
		
							} ?>
							>AN</option>
                            <option value="CP"
							<?php
							if($value['type']!=NULL || $value['type']!='')
							{
								foreach($array as $value2)
								{
									if($value2=='CP'){
										echo 'selected';
										break;
									}
								}
		
							} ?>
							>CP</option>
                            <option value="O"
							<?php
							if($value['type']!=NULL || $value['type']!='')
							{
								foreach($array as $value2)
								{
									if($value2=='O'){
										echo 'selected';
										break;
									}
								}
		
							} ?> >O</option>
                        </select>
                    </div>
            </div>
            <!-- COMPANY NAME -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Company Name</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control" value="<?php echo $value['agregator']; ?>" id="company_name" name="company_name"  required type="text" placeholder="Company name" >
                        </div>
                    </div>
                </div>
            <!-- OTHER COMPANY NAME -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Other Company</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control"  value="<?php echo $value['other_companies_name'];?>" id="other_companies_names" name="other_companies_name"  type="text" placeholder="Other company name">
                        </div>
                    </div>
                </div>
            <!-- DOMAINS/PRODUCTS -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Domains</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                       <div class="col-10">
                            <input class="form-control" value="<?php echo $value['domain_product'];?>" id="domain_product" name="domain_product"  type="text" placeholder="Domains/Products">
                        </div>
                    </div>
                </div>
            <!-- CONTACT NAMES -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Contact Name</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control" value="<?php echo $value['accountName'];?>" id="accountName" name="accountName" type="text" placeholder="Contact name">
                        </div>
                    </div>
                </div>
            <!-- EMAIL -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Email</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
							<?php if($value11==true){ ?>
								 <input class="form-control" value="<?php echo $value['email'];?>" id="email" name="email"  type="email" placeholder="Email">
							<?php }
							else{?>
								 <input class="form-control" disabled id="email" name="email"  type="email" placeholder="Email">
								 <input type="hidden" name="email" id="email" value="<?php echo $value['email'];?>">
							<?php }?>
                           
                        </div>
                    </div>
                </div>
            <!-- SKYPE -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Skype</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                         <div class="col-10">
							<?php if($value11==true){ ?>
								 <input class="form-control" value="<?php echo $value['skype'];?>" id="skype" name="skype" type="text" placeholder="Skype name">
							<?php }
							else{?>
								 <input class="form-control" disabled id="skype" name="skype"  type="text" placeholder="Skype name">
								 <input type="hidden" name="skype" id="skype" value="<?php echo $value['skype'];?>">
							<?php }?>
                           
                        </div>
                    </div>
                </div>
            <!-- OFFICE LOCATION -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Office Location</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control" value="<?php echo $value['office_location']?>" id="office_location" name="office_location" type="text" placeholder="Office location">
                        </div>
                    </div>
                </div>
            <!-- CONTENTS -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Contents</div>
                        </div>
                    </div>
                        <div class="col-md-9 nopad-r">
                            <select class="form-control selectpicker filter-option" multiple data-size="5" data-selected-text-format="count>2" id="content[]" name="content[]">
								<?php
								if($value['content']!=NULL || $value['content']!='')
								{	
									$array=explode(',',$value['content']);
								}
								?>
                                <!--<option value="AD,MS,DAT,APP" >All</option>-->
								<!--<option value="All" >All</option>-->
                                <option value="AD"
								<?php
								if($value['content']!=NULL || $value['content']!='')
								{
									foreach($array as $value2)
									{
										if($value2=='AD'){
											echo 'selected';
											break;
										}
									}
		
								}?>	
								
								> AD</option>
                                <option value="MS"
								<?php
								if($value['content']!=NULL || $value['content']!='')
								{
									foreach($array as $value2)
									{
										if($value2=='MS'){
											echo 'selected';
											break;
										}
									}
		
								}?>		
								> MS</option>
                                <option value="DAT"
								<?php
								if($value['content']!=NULL || $value['content']!='')
								{
									foreach($array as $value2)
									{
										if($value2=='DAT'){
											echo 'selected';
											break;
										}
									}
		
								}?>	
								
								> DAT</option>
                                <option value="APP"
								<?php
								if($value['content']!=NULL || $value['content']!='')
								{
									foreach($array as $value2)
									{
										if($value2=='APP'){
											echo 'selected';
											break;
										}
									}
		
								}?>		
								> APP</option>
                            </select>
                        </div>
                </div>
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Product Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Geos</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r" style="width:178px;">
                        <select class="selectpicker clean-select" id="geo[]" name="geo[]" multiple data-size="5" data-selected-text-format="count>2" data-live-search="true">
							<?php 
							if(!empty($geo) && empty($clientgeo)){
							foreach($geo as $value1){ ?>
							<option value="<?php echo $value1['id'];?>"><?php echo $value1['name'];?></option>
							<?php 
							}
	
							} 
							elseif(!empty($geo) && !empty($clientgeo)){
							foreach($geo as $value1){
							$result="";
							foreach($clientgeo as $value2){
           
							if($value2['ct_id']==$value1['id']){
							$result="selected";
							}
            
							}?>
							
							<option value="<?php echo $value1['id'];?>" <?php echo $result;?>><?php echo $value1['name'];?></option>
							
							<?php 
							
							}
							} 
					?>
						
                        </select>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">
                                Confirm Nos
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                             <select class="form-control"  name="confirm_nos" id="confirm_nos">
							<option value="" disabled selected>Confirm nos</option>
							<option value="E" <?php if($value['confirm_nos']=='E'){echo 'selected';} ?>>E</option>
							<option value="P" <?php if($value['confirm_nos']=='P'){echo 'selected';} ?>>P</option>			
						</select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Platform</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control" value="<?php echo $value['platform'];?>" id="platform" name="platform" type="text" placeholder="Platform">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Username</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                       <div class="col-10">
							<?php if($value11==true){ ?>
								<input class="form-control" value="<?php echo $value['username'];?>" id="username" name="username" type="text" placeholder="Username">
							<?php }
							else{?>
								<input class="form-control" disabled id="username" name="username" type="text" placeholder="Username">	
								 <input type="hidden" name="username" id="username" value="<?php echo $value['username'];?>">
							<?php }?>   
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
						
                            <div class="text-center 1line">Password</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                         <div class="col-10">
							<?php if($value11==true){ ?>
								  <input class="form-control"  value="<?php echo $value['password'];?>" id="password" name="password"  type="text" placeholder="Password">
							<?php }
							else{?>
								  <input class="form-control"  disabled id="password" name="password"  type="text" placeholder="Password">
								   <input type="hidden" name="password" id="password" value="<?php echo $value['password'];?>">
							<?php }?>   
                          
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Financial Contact</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control"  value="<?php echo $value['financial_contacts'];?>" id="financial_contact" name="financial_contacts" type="text" placeholder="Financial Contact">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Other Emails</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
							<?php if($value11==true){ ?>
								
								 <input class="form-control" value="<?php echo $value['other_emails'];?>" id="other_emails" name="other_emails" type="text" placeholder="Other emails">
							<?php }
							else{?>
								 <input class="form-control" disabled id="other_emails" name="other_emails" type="text" placeholder="Other emails">
								 <input type="hidden" name="other_emails" id="other_emails" value="<?php echo $value['other_emails'];?>">
								
							<?php }?>   
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Other Skype</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
							<?php if($value11==true){ ?>
								<input class="form-control" value="<?php echo $value['other_skypes'];?>" id="other_skypes" name="other_skypes" type="text" placeholder="Other skype name">
								
							<?php }
							else{?>
								<input class="form-control" disabled id="other_skypes" name="other_skypes" type="text" placeholder="Other skype name">
								<input type="hidden" name="other_skypes" id="other_skypes" value="<?php echo $value['other_skypes'];?>">
							<?php }?>   
                        </div>
                    </div>
                </div>
            </div>
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Contact Information-->
            <!-- SAVE BUTTON -->
            <div class="col-md-12 nopad">     
                <div class="col-lg-5"></div>
                <div>
					<input type="hidden" value="<?php echo $value['ag_id'];?>" name="ag_id" id="ag_id">
                    <button class="ok" id='submit' type="submit">SAVE</button>
					
                </div>
                <div class="col-lg-5"></div>
            </div>
    </div>
</form>

    <?php }} else{ ?>
<div class="container-client">
<div class="alert alert-success" style="display:none">
  <strong>Success!</strong> Client Created Successfully.
</div>
<div class="alert alert-danger" style="display:none">
  <strong>Error!</strong> Failed To Create Client. Check If Company Name Already Exist.
</div>
      <!--UPLOAD FILE-->
     <form enctype="multipart/form-data" id="form1" name="form1">
      <div class="col-md-12 padd-l-0">   
        <div class="id-container col-md-12"> 
            <div class="marg-b col-md-12"><b>IO</b></div>
            <input id="io[]" multiple="multiple" name="io[]" type="file" class="col-md-6 clean-select1" >
            <div class="marg-t col-md-6">
                <div class="marg-l inline-block">Private Profile</div>
                <input type="checkbox" name="private_profile" value="1" class="float-left clean-select1">
            </div>
        </div>

            <!-- GRAPHIC CONTAINER --> 
                  
      </div><br><br>
      <!--PRIVATE PROFILE CHECKBOX-->
    
    <div class="row col-md-6 marg-l-0">
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Client Information -->
            <div>
                <!--AM-->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">
                                AM</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <select class="form-control filter-option clean-select1" id="am" name="am">
                            <option disabled selected value="">Select AM
                            </option>
                            <?php if(isset($am)){
                                foreach($am as $value1){ ?>
                            <option value="<?php echo $value1['am'];?>">
                                <?php echo $value1['am'];?>
                            </option><?php 
                                } }?>
                        </select>
                    </div>
                </div>
                <!--STATUS-->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">
                                Status
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <select class="form-control filter-option clean-select1" id="status2" name="status">
                            <option disabled selected value="">
                            Status
                            </option>
                            <option value="Lead">
                                Lead
                            </option>
                            <option value="Ongoing">
                                Ongoing
                            </option>
                            <option value="Live">
                                Live
                            </option>
                            <option value="Unfit">
                                Unfit
                            </option>
                            <option value="Paused">
                                Paused
                            </option>
                            <option value="Idle">
                                Idle
                            </option>
                        </select>
                    </div>
                </div>
            </div>      
            <div class="col-md-6">
                <div class="col-md-3 nopad">
                    <div class="ititle">
                        <div class="text-center">Type</div>
                    </div>
                </div>
                    <div class="col-md-9 nopad-r">
                        <select class="form-control selectpicker clean-select1" multiple data-size="5" data-selected-text-format="count>2" data-live-search="true" name="type[]" id="type[]">
                            <!--<option value="AN,CP,O" selected>All</option>-->
                            <option value="AN">AN</option>
                            <option value="CP">CP</option>
                            <option value="O">O</option>
                        </select>
                    </div>
            </div>
            <!-- COMPANY NAME -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Company Name</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="company_name" name="company_name" required type="text" placeholder="Company name">
                        </div>
                    </div>
                </div>
            <!-- OTHER COMPANY NAME -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Other Company</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="other_companies_names" name="other_companies_name"  type="text" placeholder="Other company name">
                        </div>
                    </div>
                </div>
            <!-- DOMAINS/PRODUCTS -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Domains</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="domain_product" name="domain_product"  type="text" placeholder="Domains/Products">
                        </div>
                    </div>
                </div>
            <!-- CONTACT NAMES -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Contact Name</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="accountName" name="accountName" type="text" placeholder="Contact name">
                        </div>
                    </div>
                </div>
            <!-- EMAIL -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Email</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="email" name="email"  type="email" placeholder="Email">
                        </div>
                    </div>
                </div>
            <!-- SKYPE -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Skype</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="skype" name="skype" type="text" placeholder="Skype name">
                        </div>
                    </div>
                </div>
            <!-- OFFICE LOCATION -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Office Location</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="office_location" name="office_location" type="text" placeholder="Office location">
                        </div>
                    </div>
                </div>
            <!-- CONTENTS -->
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Contents</div>
                        </div>
                    </div>
                        <div class="col-md-9 nopad-r">
                            <select class="form-control selectpicker filter-option clean-select1" multiple data-size="5" data-selected-text-format="count>2" id="content[]" name="content[]">
                               <!-- <option value="AD,MS,DAT,APP" selected>All</option>-->
                                <option value="AD">AD</option>
                                <option value="MS">MS</option>
                                <option value="DAT">DAT</option>
                                <option value="APP">APP</option>
                            </select>
                        </div>
                </div>
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Product Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center">Geos</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r" style="width:178px;">
                        <select class="selectpicker clean-select1" id="geo[]" name="geo[]" multiple data-size="5" data-selected-text-format="count>2" data-live-search="true">
                            <?php if(isset($geo)){
                            foreach($geo as $value){ ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                            <?php 
                            } }?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">
                                Confirm Nos
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <select class="form-control filter-option clean-select1"  name="confirm_nos" id="confirm_nos">
                            <option value="" disabled selected>Confirm Nos</option>
                            <option value="E" >E</option>
                            <option value="P" >P</option>           
                        </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Platform</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="platform" name="platform" type="text" placeholder="Platform">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Username</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="username" name="username" type="text" placeholder="Username">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Password</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="password" name="password"  type="text" placeholder="Password">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Financial Contact</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="financial_contact" name="financial_contacts" type="text" placeholder="Financial Contact">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Other Emails</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="other_emails" name="other_emails" type="text" placeholder="Other emails">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3 nopad">
                        <div class="ititle">
                            <div class="text-center 1line">Other Skype</div>
                        </div>
                    </div>
                    <div class="col-md-9 nopad-r">
                        <div class="col-10">
                            <input class="form-control clean-select1" id="other_skypes" name="other_skypes" type="text" placeholder="Other skype name">
                        </div>
                    </div>
                </div>
            </div>
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////  Contact Information-->
            <!-- SAVE BUTTON -->
            <div class="col-md-12 nopad">     
                <div class="col-lg-5"></div>
                <div>
                    <button class="ok" id='submit' type="submit">SAVE</button>
                </div>
                <div class="col-lg-5"></div>
            </div>
    </div>
</form>
<?php }?>
   <div class="col-md-6 nopad right-container">
        <div class="tab-wrapper">
			<ul class="tab-menu"><!-- TABS -->
                <li class="tab active" rel="tab1" id='historyandcomment' style="height: 42px; padding-top: 11px; width: 186px; text-align: center;">History & Comments</li>
                <li class="tab" rel="tab2" style="height: 42px; padding-top: 11px; width: 186px; text-align: center;">Issues & Violations</li>
            </ul>
			<div class="col-md-12 padd-0 tab-content"><!-- /////////////////////////////////////// LIVE CHAT SUPPORT STRUCTURE //////////////////////////////////////////////////// -->
                <div id="container">
					<div id="messages">
					</div>
                        <!-- ends #messages -->	
					<div id="text-input">
                        <input class="new">
                        <input type='text' class="input" id='input1' contenteditable="true">
                        <button class="send">Send</button>
                    </div><!-- ends #text-input -->
                </div><!-- ends #container -->
                      <!-- ****************************ANOTHER TAB*******************************-->
                <div id="container">
					<div id="messages2">
                    </div>
                        <!-- ends #messages -->
                   <div id="text-input">
                        <input class="new">
                        <input class="input" type='text' id='input2' contenteditable="true">
                        <button class="send">Send</button>
                    </div><!-- ends #text-input -->
                </div><!-- ends #container -->
            </div><!-- tab-content--> 
        </div><!-- //tab-wrapper -->
    </div><!-- right-container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <!-- Custom JavaScript -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="/js/client/client.js"></script>
    <!-- Inline Jquery -->
<script>

	
	
    var url="/client";
	
	//************************************* for chat ****************************************************************************//
	//*************************************           ****************************************************************************// 
	var $wrapper = j('.tab-wrapper'),
    $allTabs = $wrapper.find('.tab-content > div'),
    $tabMenu = $wrapper.find('.tab-menu li'),
    $line = j('<div class="line"></div>').appendTo($tabMenu);
    $allTabs.not(':first-of-type').hide();  
    $tabMenu.filter(':first-of-type').find(':first').width('100%')
    $tabMenu.each(function(i) {
        j(this).attr('data-tab', 'tab'+i);
    });
    $allTabs.each(function(i) {
        j(this).attr('data-tab', 'tab'+i);
    });
    $tabMenu.on('click', function() {
        var dataTab = j(this).data('tab'),
        $getWrapper = j(this).closest($wrapper);
        $getWrapper.find($tabMenu).removeClass('active');
        j(this).addClass('active');
        $getWrapper.find($allTabs).hide();
        $getWrapper.find($allTabs).filter('[data-tab='+dataTab+']').show();
    });
	
	var uid='<?php echo $uid;?>';	
    //display chat content for the first time starts
	j.ajax({
        url: url+"/getHistoryInfo",
        type: "POST",
        dataType:"json", 
        contentType: false,
        cache: false,
        processData: false,
        success:function(obj){
			console.log(uid);
			j("#messages").empty();
            var rows = '';
            for(i=0;i<obj.length;i++)
            {
				console.log(obj[i].uid);
                     //var matches = str.match(/\b(\w)/g);        
                     //var acronym = matches.join('');  
                if(obj[i].uid==uid)
                {
				    console.log('find match history');
                    rows+="<div class='sender'><div class='message'><p>" + obj[i].name + "</p><div class='blob'><span class='blob-content'><p>" + obj[i].text + "</p><p style='font-size: 10px; color:#00ffaa; float: right; display: inline-block;'>" + obj[i].time + "</></p></span></div></div></div>";
                }
                else
                {
                    rows+="<div class='receiver'><div class='message'><p>" + obj[i].name + "</p><div class='blob'><span class='blob-content'><p>" + obj[i].text + "</p><p style='font-size: 10px; color:#00ffaa; float: right; display: inline-block;'>" + obj[i].time + "</></p></span></div></div></div>";
                }
            }
					
		    j("#messages").append(rows);
				
			var $box = j('#messages'); 
			var height = $box.get(0).scrollHeight;
			$box.scrollTop(height);			                                  
        },
        error:function(){
                    
        }            
	}); 
		 
   j.ajax({
        url: url+"/getIssueInfo",
        type: "POST",
        dataType:"json", 
        contentType: false,
        cache: false,
        processData: false,
        success:function(obj){
		j("#messages2").empty();
	    var rows = '';
        for(i=0;i<obj.length;i++)
        {
            var str=obj[i].name;
		    if(obj[i].uid==uid)
            {
				console.log('find match issues');
                rows+="<div class='sender'><div class='message'><p>" + obj[i].name + "</p><div class='blob'><span class='blob-content'><p>" + obj[i].text + "</p><p style='font-size: 10px; color:#00ffaa; float: right; display: inline-block;'>" + obj[i].time + "</></p></span></div></div></div>";
            }
			else
            {
                rows+="<div class='receiver'><div class='message'><p>" + obj[i].name + "</p><div class='blob'><span class='blob-content'><p>" + obj[i].text + "</p><p style='font-size: 10px; color:#00ffaa; float: right; display: inline-block;'>" + obj[i].time + "</></p></span></div></div></div>";
            }
        }
		j("#messages2").append(rows);
		
        },
        error:function(){
                    
        }            
    }); 
	
	j('body').on('click', '.tab', function() {
		var $box1 = j('#messages'); 
		var height = $box1.get(0).scrollHeight;
		$box1.scrollTop(height);	
		
		var $box1 = j('#messages2'); 
		var height = $box1.get(0).scrollHeight;
		$box1.scrollTop(height);
	});
	
	//ends
	
	var callback = function() {
		var text;
				   //reset input field
		
		
		var uid='<?php echo $uid;?>';
		if(j('#historyandcomment').hasClass('active')) {
			type_chat=1;
			text=j('#input1').val();
			j('#input1').val('');
			
		}
		else {
			type_chat=0;
			text=j('#input2').val();
			j('#input2').val('');
			
		}
		
		var formData = new FormData();
		formData.append('uid', uid);
		formData.append('text', text);
		formData.append('type_chat', type_chat);              
        j.ajax({
            url: url+"/chatSave",
            type: "POST",
            dataType:"json", 
            data:formData,
            contentType: false,
            cache: false,
            processData: false,
            success:function(obj){
			if(type_chat==1)
			{
				j("#messages").empty();
		    }
			else if(type_chat==0){
				j("#messages2").empty();
			}
            var rows = '';
            for(i=0;i<obj.length;i++)
            {
                var str=obj[i].name;
				//console.log('name:'+str);
                var matches = str.match(/\b(\w)/g);        
                var acronym = matches.join('');  
                if(obj[i].uid===uid)
                {
                    rows+="<div class='sender'><div class='message'><p>" + obj[i].name + "</p><div class='blob'><span class='blob-content'><p>" + obj[i].text + "</p><p style='font-size: 10px; color:#00ffaa; float: right; display: inline-block;'>" + obj[i].time + "</></p></span></div></div></div>";
                }
                else
                {
                    rows+="<div class='receiver'><div class='message'><p>" + obj[i].name + "</p><div class='blob'><span class='blob-content'><p>" + obj[i].text + "</p><p style='font-size: 10px; color:#00ffaa; float: right; display: inline-block;'>" + obj[i].time + "</></p></span></div></div></div>";
                }
            }
		    if(type_chat==1)
			{
				j("#messages").append(rows);
			}
			else if(type_chat==0){
				j("#messages2").append(rows);
			}
                                                    
			},
			error:function(){
                    
            }            
        }); 
    };

    //on enter press or send button press perform the same task 
    
    j(".input").keypress(function() {
        if (event.which == 13) callback();
    });
    j('.send').click(callback);
					
	//chat ends
	
	
	
	//************************************* for graph ****************************************************************************//
	//*************************************           ****************************************************************************// 
	var test;
	var id=null;
	var id=<?php echo $idToSend;?>;
	if(id!=-1){
		//console.log(id);
		var formdata=new FormData();
		formdata.append('id',id);
		j.ajax({
			url: "/operation/getClientInfo?clientid="+id,
			type: "POST",
			data:formdata,
			contentType: false,
			cache: false,
			processData: false,
			success:function(msg){
			//use test=msg in later case
			test = msg;
				
		
			test1();
				//alert(msg);              
		},
		error:function(){
				
		}  
	
		}); 
	}	
		
	function test1(){
		j('.months').empty();
		var rows='';
		var m,n;
		var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];  
		//for(i=0;i<test['lastmonths'].length;i++)
		/*for(i=0;i<5;i++)
		{
				
			if((typeof test['lastmonths'][i])=='undefined'){
				rows+="<p><span>Data&nbsp;&nbsp;</span><span>Unavailable</p></span>";
			}
			else{
				m=test['lastmonths'][i]['monthyear'];
				m= m.charAt(5)+m.charAt(6);
				m=parseInt(m);
				m=mlist[m-1];	
				rows+="<p><span>"+m+"&nbsp;&nbsp;</span><span>"+test['lastmonths'][i]['revenue']+"$</p></span>";
			}
				
		}*/
		
		var len=test['lastmonths'].length;
		console.log(len);
		if(len>=5)
		{
			for(i=4;i>=0;i--)
			{
				m=test['lastmonths'][i]['monthyear'];
				m= m.charAt(5)+m.charAt(6);
				m=parseInt(m);
				m=mlist[m-1];	
				rows+="<p><span>"+m+"&nbsp;&nbsp;</span><span>"+test['lastmonths'][i]['revenue']+"$</p></span>";	
			}
		}
		else{
			for(i=(len-1);i>=0;i--)
			{
				m=test['lastmonths'][i]['monthyear'];
				m= m.charAt(5)+m.charAt(6);
				m=parseInt(m);
				m=mlist[m-1];	
				rows+="<p><span>"+m+"&nbsp;&nbsp;</span><span>"+test['lastmonths'][i]['revenue']+"$</p></span>";	
			}	
			for(i=0;i<5-len;i++){
				rows+="<p><span>Data&nbsp;&nbsp;</span><span>Unavailable</p></span>";	
			}
			
		}
		
		j('.months').append(rows);
		rows='';
		j('.offers').empty();
		//for(i=0;i<test['lastfiveoffers'].length;i++)
		for(i=0;i<5;i++)	
		{
			if((typeof test['lastfiveoffers'][i])=='undefined'){
				rows+="<p><span>Data&nbsp;&nbsp;</span><span>Unavailable</p></span>";
			}
			else{
				rows+="<p><span>"+test['lastfiveoffers'][i]['campaign']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class='device'>"+test['lastfiveoffers'][i]['offer']+"</span></p>";
			}	
		}
		j('.offers').append(rows);
		
		
		//for graph
		
		var ctx = document.getElementById("myChart");
		//get data and labels
		//get data
		//var data1='';
		var string12=new Array();
		var label12=new Array();
		var datevalue,datevalue1;
		for(i=0;i<test['daybyday'].length;i++)
		{
			string12[i]=test['daybyday'][i]['revenue'];
			
			datevalue=test['daybyday'][i]['date'];
			datevalue1= datevalue.charAt(5)+datevalue.charAt(6);
			datevalue1=parseInt(datevalue1);
			datevalue1=mlist[datevalue1-1];
			label12[i]='"'+datevalue1+" "+(datevalue.charAt(8)+datevalue.charAt(9))+'"';
		}	
		
		console.log(label12);
		//label12=["March 30", "March 31", "April 1", "April 2", "April 3", "April 4","April 5"];
	
		
		//var string12=[268.144,220.111,4.200,8.050,25.638,44.654,13.300];
		var data = {
		labels: label12,
		datasets: [
        {
            label: "Day By Day Revenue",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(75,192,192,0.4)",
            borderColor: "rgba(75,192,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 20,
            data: string12,
			//data:[data1],
            spanGaps: false,
			}
			]
		};
            var scatterChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
			scales: {
            xAxes: [{
                stacked: true,
				scaleLabel: {
					//display: true,
					//labelString: 'Days'
				}
            }],
            yAxes: [{
                stacked: true,
				 scaleLabel: {
					display: true,
					labelString: 'Revenue'
				}
            }]
			}
			}

       });
	} 
	
</script>
	
{% endblock %}
{% block simplescript %}
{% endblock %}