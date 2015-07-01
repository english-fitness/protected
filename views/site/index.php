
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Icon system-->
    <link rel="shortcut icon" href="https://speakup.vn/news/wp-content/uploads/2015/06/android-chrome-96x961.png" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/media/css/bootstrap/bootstrap.min.css" />

    <!-- Optional theme -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/media/css/bootstrap/bootstrap-theme.min.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/media/css/bootstrap/login.css" />
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <?php
        $tenmien="http";
        if(isset($_SERVER['HTTPS'])){
            $tenmien.="s";
        }
        $tenmien.="://".$_SERVER['SERVER_NAME'];
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-4 ">
                <img class="trai-img" src="<?php echo Yii::app()->baseUrl; ?>/media/images/trai.png" alt="">
            </div>
            <div class="col-sm-6 col-md-4 ">
                <div class="account-wall">
		    <a href="<?php echo $tenmien?>">
                        <img class="profile-img" src="<?php echo Yii::app()->baseUrl; ?>/media/images/logo-speakup.png" alt="">
		    </a>
                    <form name="login-form" class="form-signin" action="<?php echo Yii::app()->baseurl;?>/site/signin" method="post">
                        
                        <input name="username" type="text" class="form-control" placeholder="Username ..." required autofocus>
                        <input name="password" type="password" class="form-control" placeholder="Password ..." required>
                        <span class="text-center test-error">
                            <?php 
                            foreach(Yii::app()->user->getFlashes() as $key => $message) : 
                                echo  $message; 
                            endforeach;
                            ?>
                        </span>
                        <a href="<?php echo $tenmien."/news/dang-ky" ?>" class="pull-left new-account">Create an account? </a> 
                        <a href='<?php echo $tenmien."/news/lien-he"?>' class="pull-right need-help">Need help? </a>
                        <span class="clearfix"></span>
                        <button class="btn btn-lg btn-warning btn-block btn-login" type="submit">
                            Sign in</button>
                    </form>
                </div>
                
            </div>
            <div class="col-sm-3 col-md-4 ">
                <img class="phai-img" src="<?php echo Yii::app()->baseUrl; ?>/media/images/phai.png" alt="">
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo Yii::app()->baseUrl; ?>/media/js/bootstrap/jquery.min.js"></script>
      
    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo Yii::app()->baseUrl; ?>/media/js/bootstrap/bootstrap.min.js"></script>

	<script>
  		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  		ga('create', 'UA-64042265-1', 'auto');
  	ga('send', 'pageview');

</script>
  </body>
</html>
