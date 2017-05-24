<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta https-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Mobistein</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/img/mobipium-favicon.png"/>

        <style>
            body {
                padding-top: 100px;
                padding-bottom: 20px;
            }
        </style>

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/custom-sky-forms.css">
        
        <link rel="stylesheet" href="/css/main.css">

        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <!--[if lt IE 9]>
            <link rel="stylesheet" href="o/css/sky-forms-ie8.css">
        <![endif]-->
    </head>

    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="col-md-10 col-md-offset-1">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand navbar-brand-home" href="">
                  <img style="    padding-top: 22px;" src="/img/mobipium-logo.svg" alt="">
                  <span class="beta blue"></span>
              </a>
            </div>
            <div class="navbar-collapse collapse">
             
              <?php echo $this->tag->form(array('session/start', 'role' => 'form', 'class' => 'navbar-form navbar-right')); ?>
                <div class="form-group">
                 <?php echo $this->tag->textField(array('username', 'class' => 'form-control', 'placeholder' => 'User')); ?>
                </div>
                <div class="form-group">
                <?php echo $this->tag->passwordField(array('password', 'class' => 'form-control', 'placeholder' => 'Password')); ?>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
              </form>
            </div><!--/.navbar-collapse -->
        </div>
      </div>
    </div>
    <div id="wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <div class="text-justify home-content">
                        <div class="home-title">MOBISTEIN</div>
                        <div class="sub-title">Mobile Reports</div>
                        <p class="home-text">Reports for idiots.</p>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-1">
                    <!--exform-->
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">Copyright &copy; 2017 Mobistein. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>    
    <!-- // <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
    <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
    <script src="/js/vendor/bootstrap.min.js"></script>
    <script src="/plugin/sky-forms/js/jquery-ui.min.js"></script>
        
    <script src="/js/main.js"></script>
    <script type="text/javascript">        
        </script>
        <!--[if lt IE 10]>
            <script src="/plugin/sky-forms/js/jquery.placeholder.min.js"></script>
        <![endif]-->        
        <!--[if lt IE 9]>
            <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <script src="/plugin/sky-forms/js/sky-forms-ie8.js"></script>
        <![endif]-->
    </body>
</html>