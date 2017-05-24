{% extends "/headfooter.volt" %}
{% block title %}<title>Country Manager</title>{% endblock %}
{% block scriptimport %}
<?php  $url=""; ?>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">  
<link rel="stylesheet" type="text/css" href="<?php echo $url;?>/css/master-of-puppets.css"> 

  <div class="container">
     <div class="row">
        <div class="col-md-2 col-lg-1"></div>
        <!--Start of LSide-->
        <div class="col-md-4 col-lg-5">
            <!--User-->
            <div class="row" id="toprow">
                <div class="col-xs-2 col-md-6">
                        <div id="titlerow">Users</div>
                </div>
                <div class="col-xs-10 col-md-6">
                        <input class="searchbar" id="search1" placeholder="Search..."></input>
                </div>
            </div>           
            <div class="row">
                <div class="col-xs-12 col-md-12">
                   <div id="users">
                        <?php  
                        if(!empty($user))
                        { 

                            foreach($user as $value) 
                            { 
                                                
                            
                            ?>
                                <div class="row oddEven usersElements userid" id='<?php echo $value->id;?>'>
                                    <div class="col-xs-1 col-md-1"></div>
                                    <div class="col-xs-9 col-md-9 col-lg-8">
                                        <span class='point' id="items<?php echo $value->id;?>"><?php echo ucfirst($value->username);?></span>
                                    </div>
                                    <div class="col-xs-1 col-md-1">
                                        <div class="colorsByUser" ></div>
                                    </div>
                                    <div class="col-xs-1 col-md-1"></div>
                                </div>
                            <?php } 
                        }?>
                    </div>
                </div>
            </div>
            <!--User Agg-->
            <div class="row" id="toprow">
                <div class="col-xs-2 col-md-6">
                        <div id="titlerow">User's Countries</div>
                </div>
                <div class="col-xs-10 col-md-6">
                        <input class="searchbar" id="search" placeholder="Search..."></input>
                </div>
            </div> 
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div id="countries">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-xs-1 col-md-1"><button id="rmct"><img id="remove" src="<?php echo $url;?>/img/admin/minus.svg"></button></div>
                    <div  class="col-xs-8 col-md-7" id="remlab">Remove Country</div>
                </div>
            </div>
        </div>
        <!--END of LSide-->

        <!--Start of RSide-->
        <div class="col-md-4  col-lg-5">
        <!--Aggregators-->
            <div class="row" id="toprow">
                <div class="col-xs-2 col-md-6">
                        <div id="titlerow">All Countries</div>
                </div>
                <div class="col-xs-10 col-md-6">
                        <input class="searchbar" id="searchUC" placeholder="Search..."></input>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12">
                    <div id="aggs">
                        <?php 
                        if(!empty($ctusr))
                        { 
                            foreach($ctusr as $value1) 
                            {
                                $username= $value1['username'];
                                if($username=="" || $username==NULL)
                                {
                                    $username="Unatributed";
                                }
                                $id='ctusr'.$value1['id'];?>
                                <div class="row oddEven allCountries" id=<?php echo $value1['uid'];?> >
                                    <div id='<?php echo $id;?>'>
                                        <div class='col-xs-1 col-md-1'></div>
                                        <div class='col-xs-1 col-md-1'>
                                            <input type='checkbox' name='ctusr[]' value=<?php echo $value1['id'];?> value="2">
                                        </div>
                                        <div class='col-xs-8 col-md-7 col-lg-6'> 
                                            <span id='items'><?php echo $value1['country'];?></span> 
                                        </div>
                                        <div class='col-xs-2 col-md-2'>
                                            <?php if($username!='Unatributed'){
                                                $aliasArr = explode(',', $username);
                                                if(sizeof($aliasArr) > 1 )
                                                {

                                                    $username=join(',', array_map('ucfirst', explode(',', $username)));
                                                ?>

                                                    <a href='#' data-toggle='tooltip' title=''><img data-toggle='popover' data-placement='top' data-content='<?php echo $username;?>' data-original-title='' id='trident' class='iconsTable' src="<?php echo $url;?>/img/trident.svg">
                                                    </a>
                                                <?php

                                                }
                                                else{?>       
                                                    <div class='colorsByUsers'><?php echo ucfirst($username);?></div>
                                                <?php }

                                            }  
                                            else{?>
                                                <div class='colorsByUsers' ><?php echo $username;?></div>
                                           <?php } ?>
                                        </div>
                                        <div class='col-xs-1 col-md-1'></div>
                                    </div>
                                </div>
                                
                            <?php        
                            }
                        }?>
                    </div>
                </div>
            </div>
           <div class="row">
                <div class="col-md-12">
                    <div class="col-xs-1 col-md-1"><button id="addct"><img id="add" src="<?php echo $url;?>/img/admin/plus.svg"></button></div>
                    <div  class="col-xs-8 col-md-7"  id="addlab">Add Country</div>
                </div>
            </div>
            
        </div>
        <div class="col-md-2 col-lg-1"></div>
     </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?php echo $url;?>/js/admin/scriptsCountries.js"></script> 

    <script type="text/javascript">
        $('.iconsTable').popover({ trigger: "hover" });
    </script>    
{% endblock %}
{% block simplescript %}
{% endblock %}