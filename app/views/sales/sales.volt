{% extends "/headfooter.volt" %}
{% block title %}<title>CRM - Sales</title>
{% endblock %}
{% block scriptimport %}    

	
<!-- CRM Sales -->

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">






<!-- Latest compiled and minified CSS -->



<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />



<!--Datepicker CSS-->

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />







<!--ROBOTO FONTS-->



<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,500,700">



<!--FONTAWESOME-->



<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">



<!-- CUSTOM CSS -->



<link rel="stylesheet" type="text/css" href="/css/master-of-sales.css">



<style>

	.bootstrap-select.form-control:not([class*=col-]) {



		width: 90%;



	}

</style>



<!-- START SALES -->




{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}


<form method='POST' action='/sales/createExcel'>



<div class="container-fluid">



    <div class="row nomar">



        <h3 class="roboto">Sales Stats</h3>







        <div class="col-md-12">



            <div class="col-xs-4 col-sm-4 col-md-1 nopad">



                <div class="stitle">



                    <div class="roboto-condensed">SELECT COUNTRY</div>



                </div>



            </div>







            <div class="col-xs-8 col-sm-8 col-md-2 nopad">



                <div>



                  <label class="none" for="sel1"></label>



                      <select class="form-control selectpicker" name='countries' id='countries'  data-show-subtext="true" data-live-search="true">



                            <option value=''>Choose Country</option>



                            <?php if(isset($countries)&& !empty($countries)): 



                            foreach($countries as $value): ?>



                            <option value='<?php echo $value['id'];?>'><?php echo $value['name']; ?></option>



                            <?php endforeach;



                            endif; ?>



                      </select>



                </div>



            </div><!-- END SELECT COUNTRY INPUT col-xs-8 col-sm-8 col-md-8 nopad -->







            <div class="col-xs-4 col-sm-4 col-md-1 nopad">



                <div class="stitle">



                    <div class="roboto-condensed">SELECT CARRIER</div>



                </div>



            </div>







            <div class="col-xs-8 col-sm-8 col-md-2 nopad">



                <div>



                  <label class="none" for="sel1"></label>



                      <select class="form-control selectpicker" multiple style='display:none;' data-size="5" data-selected-text-format="count>2" name='carriers[]' id='carriers'  data-show-subtext="true" data-live-search="true" >

                      </select>



                </div>



            </div><!-- END SELECT CARRIER INPUT col-xs-8 col-sm-8 col-md-8 nopad -->







            <div class="col-xs-4 col-sm-4 col-md-1 nopad">



                <div class="stitle">



                    <div class="roboto-condensed">SELECT DATE</div>



                </div>



            </div> 







            <div class="col-xs-8 col-sm-8 col-md-2 nopad">



            <input class="form-control" type="text" name="date" id="date" style="width:100%" value='<?php echo date('Y/m/d',strtotime("-1 days"));?> - <?php echo date('Y/m/d',strtotime("-1 days"));?>' />



            </div><!-- END SELECT DATEPICKER col-xs-8 col-sm-8 col-md-8 nopad -->







            <div class="col-xs-8 col-sm-8 col-md-3 nopad">



                <div class="col-md-10 text-right nopad">



                    <button type='button' name='search' id='search' style="background: transparent; border: none; width: 3vw;cursor: pointer;vertical-align: middle"><img class="excel" src="/img/searchingnemo.svg"></button>



                </div><!-- END SEARCH BUTTON -->



                <div class="text-right">



                    <button type='submit' name='export' id='export' style="background: transparent; border: none; width: 3vw;cursor: pointer;vertical-align: middle"><img class="excel" src="/img/d_excel.svg"></button>



                </div><!-- END EXCEL BUTTON -->



            </div>



        </div><!-- END COL-MD-12 -->







        <!-- SALES DATATABLE -->







        <div class="row  nomar">



            <div class="col-md-12" style="margin-top:30px;">







                    <table class="table">



                        <thead class="head-color thead-inverse">



                            <tr>



                                <th style="border-top-left-radius: 10px; border-left:1px solid transparent;">PARAMETERS</th>



                                <th>CLICKS</th>



                                <th>CONVERSIONS</th>



                                <th>CPA</th>



                                <th>CR</th>



                                <th style="border-top-right-radius: 10px; border-right:1px solid transparent;">EPC</th>



                            </tr>



                        </thead>



                        <tbody id='table'>



                            <tr  class='crm-lightgrey'>



                                <td scope="row" class="rowstart"><b>AD<b></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td class="nobor"></td>



                            </tr>



                            <tr  class='crm-lightgrey'>



                                <td scope="row" class="rowstart"><b>AF<b></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td class="nobor"></td>



                            </tr>



                            <tr  class='crm-lightgrey'>



                                <td scope="row" class="rowstart"><b>MS<b></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td class="nobor"></td>



                            </tr>



                            <tr class='crm-darkgrey'>



                                <td scope="row" class="rowstart"><b>TOTAL<b></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td></td>



                                <td class="nobor"></td>



                            </tr>



                        </tbody>



                    </table>

            </div><!-- col-md-12 -->



        </div><!-- END SALES DATATABLE ROW -->



        </div>



    </div>







<!-- END SALES -->



        <!-- Minified Jquery -->



        

		
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

        <!-- Fontawesome JavaScript -->

        <script src="https://use.fontawesome.com/b9504fb171.js"></script>

        <!-- Custom JavaScript -->

        <script src="/js/sales/sales_script.js"></script>

        <script type="text/javascript">



            //////////////////////////////////////////

             $(document).ready(function () { 
			    $(".selectpicker").selectpicker();
			    $(".bootstrap-select").click(function () {
			         $(this).addClass("open");
			    });

			    $(".dropdown").click(function () {
			         $(this).addClass("open");
			    });
			  });
            


            $(function() {



                $('input[name="date"]').daterangepicker({



				"dateLimit": {



				"days": 2



				},



				"locale": {



				"format": "YYYY/MM/DD"



			}

		});



		});



        </script>
 {% endblock %}
{% block simplescript %}
{% endblock %}