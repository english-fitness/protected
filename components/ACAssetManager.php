<?php


/*
 * ACAssetManager
 * */
class ACAssetManager
{
    /*
     *  @basePath()
     *  */
    public $basePath;


    public $packageName = 'package-name';
    /*
     *  @js()
     *  */
    public $js = array();

    /*
     *  @css()
     *  */
    public $css = array();

    /*
    *  @depends()
    *  */
    public $depends = array();

    /*
    *  @register($view)
    *  */
    public static  function register($view = null,$class)
    {
        $classes = new $class();
        return $classes->assetRegister($view);
    }

    /*
    *  @assetRegister($view)
    *  */
    public function assetRegister()
    {
        $this->registerPackage();
    }

    /*
   *  @registerPackage($view)
   *  */
    public function registerPackage()
    {
        $package = array(
            'basePath'    =>$this->basePath,
            'css'         => $this->css,
            'js'          =>$this->js,
            'depends'=>$this->depends,
        );

        $cs = Yii::app()->clientScript;
        $cs = $cs->addPackage($this->packageName,$package);
        $cs->registerPackage($this->packageName);
    }
}