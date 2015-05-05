<?php

class Html extends  CHtml {

    public static function createNavMenu($menu,$htmlOptions = array()) {
        $content = null;
        $currentUri = Yii::app()->request->requestUri;
        $currentUrlStripStatus = preg_replace('/\?&?status=[^&]*/', '', $currentUri);
		if (sizeof($menu) == 1)
		{
			$item = reset($menu);
			$label = $item['label'];
			return '<div class="nav nav-tabs">'.'<p style="text-align:center; font-size:20px; padding-top:5px;">'.$label.'</p></div>';
		} else { 
			foreach($menu as $items) {
				$liHtmlOptions = isset($items['htmlOptions'])?$items['htmlOptions']:array();
				$currController = Yii::app()->controller->id;
				$menuControllers = array();//Active controller
				if(isset($items['controllers']) && is_array($items['controllers'])){
					$menuControllers = $items['controllers'];
				}
				if($currentUrlStripStatus==$items['url'] || in_array($currController, $menuControllers)) {
					$liHtmlOptions['class']='active';
				}else{
					unset($liHtmlOptions['class']);
				}
				$aHtmlOptions['href'] = $items['url'];
				$tagLiContent  = self::tag('a',$aHtmlOptions,$items['label']);
				$content .= self::tag('li',$liHtmlOptions,$tagLiContent);
			}
		}
        return self::tag('ul',$htmlOptions,$content);
    }
}