{% extends "/headfooter.volt" %}
{% block title %}<title>Security</title>{% endblock %}
{% block scriptimport %}    
    <script src="/js/invest/main.js"></script>
{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
            <div class="row">
             <div class="col-md-14"> 
                <div class="col-md-3" style="padding-top:12px">
                    
                  <!--  <form role="form" action="http://mobisteinreport.com/security/insertmask" method="post">
                       <div class="form-group">
                            <label for="jumps">Campaigns:</label>
                            <textarea class="form-control" rows="10" id="jumps" name="jumps"></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success">Upload</button>
                    </form>-->
                  <?php echo $itable ?>
                   
                 </div>
                 
                 <div class="col-md-3" style="padding-top:12px">
                     
                     
                   <!--   <form role="form" action="http://mobisteinreport.com/invest/report" method="post">
                        
                        <div class="form-group">
                            <label  for="sdate">Start Date:</label>
                            <input type="text" class="form-control datepicker" id="sdate" name="sdate">
                        </div>
                        <div class="form-group">
                            <label for="edate">End Date:</label>
                            <input type="text" class="form-control datepicker" id="edate" name="edate">
                        </div>
                        <div class="form-group">
                            <label for="source">Sources:</label>
                            <select class="form-control" id="source" name="source">
                                 <?php echo $combo; ?>
                            </select>
                        </div>
                       
                        <button type="submit" name="submit" class="btn btn-default">Report</button>
                    </form> -->
                     
                     
                 </div>
                 
<!--                <div class="col-md-3 col-md-offset-1">
                    exform
                </div>-->
            </div>
        </div>
    </div>
{% endblock %}
{% block simplescript %}
{% endblock %}