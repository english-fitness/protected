<?php

class Html extends  CHtml {

    public static function createNavMenu($menu,$htmlOptions = array()) {
        $content = null;
        $currentUri = Yii::app()->request->requestUri;
        $currentUrlStripStatus = preg_replace('/\?&?status=[^&]*/', '', $currentUri);
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
        return self::tag('ul',$htmlOptions,$content);
    }
}