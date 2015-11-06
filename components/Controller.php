<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	private $_baseAssetsUrl;
	private $_themeAssetsUrl;

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	public $subPageTitle=null;//Sub Page Title
	public $device=null;
	public $loadJQuery=true;//Load Jquery
	public $loadMathJax=false;//Load MathJax
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function init(){
		if (!Yii::app()->request->isAjaxRequest){
			$cs = Yii::app()->getClientScript();
			$cs->registerCoreScript('jquery');
			$cs->registerCoreScript('jquery.ui');
			$cs->registerCssFile($this->baseAssetsUrl."/css/jquery/jquery-ui.min.css");
		}
	}

	protected function renderJSON($data)
	{
		header('Content-type: application/json');
		echo CJSON::encode($data);

		foreach (Yii::app()->log->routes as $route) {
			if($route instanceof CWebLogRoute) {
				$route->enabled = false; // disable any weblogroutes
			}
		}
		Yii::app()->end();
	}

	public function missingAction($action)
    {
        $action=str_replace('-','',$action);
        $action='action'.ucfirst(strtolower($action));
        if(method_exists($this,$action))
            $this->$action();
        else
            $this->actionIndex();
    }
	
	/**
	 * @return string the page title. Using subPageTitle if we set it for each controller, action.
	 */
	public function getPageTitle()
	{
		if($this->subPageTitle!==null){
			return Yii::app()->name.' - '.$this->subPageTitle;
		}else{
			return parent::getPageTitle();
		}
	}

    /**
     * Render ajax view form
     */
    public function renderAjax($view,$data=null,$return=false,$processOutput=true) {
        if(Yii::app()->request->isAjaxRequest){
           $data['title'] = $this->getPageTitle();
           $data['content'] = $this->renderPartial($view,$data,true,$processOutput);
           $this->renderJSON($data);
           Yii::app()->end();
        }else{
           $this->render($view,$data,$return);
        }
    }

    /**
     * Create url with route by url 
     */
    public function  getUrl($url) {
        if(is_array($url)) {
            $route=isset($url[0]) ? $url[0] : '';
            $url=$this->createUrl($route,array_splice($url,1));
        }
        return $url;
    }
    
	/**
     * Get query param from url. Exp: ?a=1 or ?a[b]=2...
     */
	public function getQuery($param, $default=null)
	{
		if(strpos($param, '[')!==false && strpos($param, ']')!==false){
			$paramArr = explode('[', $param);//Parse param field
			$result = Yii::app()->request->getQuery($paramArr[0], array());
			$subParam = trim(str_replace(']', '', $paramArr[1]));//Field name in param
			if(isset($result[$subParam])) return $result[$subParam];
			return $default;
		}
		return Yii::app()->request->getQuery($param, $default);
	}
	
	/**
     * Get post param from submit form
     */
	public function getPost($param, $default=null)
	{
		if(strpos($param, '[')!==false && strpos($param, ']')!==false){
			$paramArr = explode('[', $param);//Parse param field
			$result = Yii::app()->request->getPost($paramArr[0], array());
			$subParam = trim(str_replace(']', '', $paramArr[1]));//Field name in param
			if(isset($result[$subParam])) return $result[$subParam];
			return $default;
		}
		return Yii::app()->request->getPost($param, $default);
	}

	//use asset manager only in production
	public function getBaseAssetsUrl(){
		if (defined('YII_DEBUG') && YII_DEBUG){
			return Yii::app()->baseUrl."/media/";
		}
		if ($this->_baseAssetsUrl === null)
			Yii::app()->getAssetManager()->linkAssets = true;
            $this->_baseAssetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application')."/../media/");
        return $this->_baseAssetsUrl;
	}

	public function getThemeAssetsUrl(){
		if (defined('YII_DEBUG') && YII_DEBUG){
			Yii::app()->theme->baseUrl."/";
		}
		if ($this->_themeAssetsUrl === null)
			Yii::app()->getAssetManager()->linkAssets = true;
            $this->_themeAssetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application')."/../".Yii::app()->theme->baseUrl."/");
        return $this->_themeAssetsUrl;
	}

}