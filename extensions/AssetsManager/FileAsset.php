<?php


/*
 * class FileAssets
 * */
class FileAsset extends ACAssetManager
{
    /*
    *  @basePath()
    *  */
    public $basePath ='ext.AssetsManager.files';


    public $packageName = __CLASS__;
    /*
     *  @js()
     *  */
    public $js = array(
	    
		'tinymce/js/tinymce/tinymce.min.js',
        'fancybox/jquery.mousewheel-3.0.4.pack.js',
        'fancybox/jquery.easing-1.3.pack.js',
		'fancybox/jquery.fancybox-1.3.4.pack.js',
        'file.js',
    );

    /*
     *  @css()
     *  */
    public $css = array(
		'file.css',
		'fancybox/jquery.fancybox-1.3.4.css'
	);

    /*
     *  @depends()
     *  */
    public $depends = array('jquery');


    /*
     *  @register()
     *  */
    public static function  register($view =null,$class = __CLASS__)
    {
        parent::register($view,$class);
    }

}