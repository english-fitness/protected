<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <?php if ($this->device == 'mobile'):?>
            <meta name="viewport" content="width=720">
        <?php else:?>
            <meta name="viewport" content="width=1366">
        <?php endif;?>
        <?php $baseAssetsUrl = $this->baseAssetsUrl?>
        <!--favicon-->
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseAssetsUrl;?>/images/favicons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="<?php echo $baseAssetsUrl;?>/images/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo $baseAssetsUrl;?>/images/favicons/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo $baseAssetsUrl;?>/images/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo $baseAssetsUrl;?>/images/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="<?php echo $baseAssetsUrl;?>/images/favicons/manifest.json">
        <link rel="shortcut icon" href="<?php echo $baseAssetsUrl;?>/images/favicons/favicon.ico">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="<?php echo $baseAssetsUrl;?>/images/favicons/mstile-144x144.png">
        <meta name="msapplication-config" content="<?php echo $baseAssetsUrl;?>/images/favicons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
        <!--end favicon-->
        <script src="<?php echo $baseAssetsUrl;?>/js/jquery/jquery-1.9.1.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/flick/jquery-ui.css">
        <script src="<?php echo $baseAssetsUrl;?>/js/bootstrap/bootstrap.min.js"></script>
        <?php if ($this->device == 'mobile'):?>
            <script type="text/javascript">
                $(document).bind('mobileinit',function(){
                    $.mobile.loadingMessage = false;
                })
            </script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
        <?php endif;?>
        <!--common js-->
        <script src="<?php echo $baseAssetsUrl;?>/home/js/landing.js"></script>
        <!---->

        <script src="<?php echo $baseAssetsUrl;?>/home/js/modernizr.js"></script>
        <script src="<?php echo $baseAssetsUrl;?>/home/js/masonry.pkgd.min.js"></script>
        <script src="<?php echo $baseAssetsUrl;?>/home/js/jquery/jquery.flexslider-min.js"></script>
        <script src="<?php echo $baseAssetsUrl;?>/home/js/jquery/jquery.testimonial-slider.js"></script>
        <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?>/home/style/jquery/jquery.testimonial-slider.css" />
        <script src="<?php echo $baseAssetsUrl;?>/js/jquery/jquery.timepickr.js"></script>
        <script src="<?php echo $baseAssetsUrl;?>/js/jquery/jquery.weekLine.min.js"></script>
        <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?>/css/jquery/jquery.weekLine.css">
        <script src="<?php echo $baseAssetsUrl;?>/js/bootstrap/bootstrap-dialog.min.js"></script>
        <link rel="stylesheet" href="<?php echo $baseAssetsUrl;?>/css/bootstrap/bootstrap-dialog.min.css">
        <script src="<?php echo $baseAssetsUrl;?>/uploads/home/data/testimonials.js"></script>
        <script src="<?php echo $baseAssetsUrl;?>/uploads/home/data/teachers.js"></script>

        <title>Speak up - Học tiếng Anh online</title>
        <script type="text/javascript" src="<?php echo $baseAssetsUrl;?>/home/js/testimonials.js"></script>
        <script src="<?php echo $baseAssetsUrl;?>/home/js/jquery.lightbox/html5lightbox.js"></script>
        <script type="text/javascript">
           var baseAssetsUrl = '<?php echo $baseAssetsUrl?>';
        </script>
        
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            // var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            // (function(){
            //     var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            //     s1.async=true;
            //     s1.src='https://embed.tawk.to/55eff32a19a4a3e632e8a76c/default';
            //     s1.charset='UTF-8';
            //     s1.setAttribute('crossorigin','*');
            //     s0.parentNode.insertBefore(s1,s0);
            // })();
        </script>
        <!--End of Tawk.to Script-->
        <!--Facebook-->
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.4";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <!--End Facebook-->
        <!--Google Analytics-->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-64701803-2', 'auto');
          ga('send', 'pageview');

        </script>
        <!--End Google Analytics-->
    </head>
    <body>
    <?php 
        $utmParams = array();
        
        if (isset($_REQUEST['utm_source']))
            $utmParams['utm_source'] = $_REQUEST['utm_source'];
        if (isset($_REQUEST['utm_medium']))
            $utmParams['utm_medium'] = $_REQUEST['utm_medium'];
        if (isset($_REQUEST['utm_term']))
            $utmParams['utm_term'] = $_REQUEST['utm_term'];
        if (isset($_REQUEST['utm_content']))
            $utmParams['utm_content'] = $_REQUEST['utm_content'];
        if (isset($_REQUEST['utm_campaign']))
            $utmParams['utm_campaign'] = $_REQUEST['utm_campaign'];

        setcookie('utmParams', json_encode($utmParams), time()+60*60, '/');
    ?>
    <?php echo $content; ?>
    </body>
</html>