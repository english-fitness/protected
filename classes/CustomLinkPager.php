<?php
class CustomLinkPager extends CLinkPager
{
	/**
	 * Customize LinkPager function: create page button
	 */
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected){
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		}
		$requestUri = Yii::app()->request->requestUri;
		$pageNumber = $page+1;//Page number to render button
		if(!(strpos($requestUri, 'page=')>0 || strpos($requestUri, 'page/'))>0){
			if(strpos($requestUri, '?')>0){
				$requestUri .= '&page='.$pageNumber;
			}else{
				$requestUri .= '?page='.$pageNumber;
			}
		}elseif(strpos($requestUri, 'page=')>0){
			$requestUri = preg_replace("/page=\d+/", "page=".$pageNumber, $requestUri);
		}elseif(strpos($requestUri, 'page/')>0){
			$requestUri = preg_replace("/page\/\d+/", "page/".$pageNumber, $requestUri);
		}
		return '<li class="'.$class.'">'.CHtml::link($label, $requestUri).'</li>';
	}
	
}