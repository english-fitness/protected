<?php
    $rand = rand(0,99);
    if ($rand < 50){
        $loginBgClass = "login-bg-1";
        $loginBoxClass = "login-box-1";
    } else {
        $loginBgClass = "login-bg-2";
        $loginBoxClass = "login-box-2";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseAssetsUrl ?>/css/bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseAssetsUrl ?>/css/login.css" />
      
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body class="<?php echo $loginBgClass?>">
        <div class="container-fluid">
            <div class="login-box-container">
                <div class="login-box <?php echo $loginBoxClass?>">
                    <div class="text-center">
            		    <a href="/">
                            <img class="logo" src="<?php echo Yii::app()->baseUrl; ?>/media/images/logo/logo-white-bordered-200.png" alt="">
            		    </a>
                    </div>
                    <form name="login-form" class="form-signin" action="<?php echo Yii::app()->baseurl;?>/site/signin" method="post">
                        
                        <input name="username" type="text" class="form-control username" placeholder="Username ..." required autofocus>
                        <input name="password" type="password" class="form-control password" placeholder="Password ..." required>
                        <span class="text-center test-error">
                            <?php 
                            foreach(Yii::app()->user->getFlashes() as $key => $message) : 
                                echo  $message; 
                            endforeach;
                            ?>
                        </span>
                        <button class="btn btn-lg btn-warning btn-block btn-login" type="submit">
                            Sign in
                        </button>
                        <div class="help-link text-center">
                            <a href="/news/dang-ky" class="new-account">Don't have an account?</a>
                            <!-- Hide need help since the contact form is not working
                            <a href='/news/lien-he' class="pull-right need-help">Need help? </a>
                            -->
                        </div>
                        <span class="clearfix"></span>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
