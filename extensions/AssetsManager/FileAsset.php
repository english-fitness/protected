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
		'jquery.fancybox/jquery.fancybox.pack.js',
        'file.js',
    );

    /*
     *  @css()
     *  */
    public $css = array(
		'file.css',
		'jquery.fancybox/jquery.fancybox.css'
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