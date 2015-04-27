<?php
/**
 * CMenu class file.
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CMenu displays a multi-level menu using nested HTML lists.
 *
 * The main property of CMenu is {@link items}, which specifies the possible items in the menu.
 * A menu item has three main properties: visible, active and items. The "visible" property
 * specifies whether the menu item is currently visible. The "active" property specifies whether
 * the menu item is currently selected. And the "items" property specifies the child menu items.
 *
 * The following example shows how to use CMenu:
 * <pre>
 * $this->widget('zii.widgets.CMenu', array(
 *     'items'=>array(
 *         // Important: you need to specify url as 'controller/action',
 *         // not just as 'controller' even if default acion is used.
 *         array('label'=>'Home', 'url'=>array('site/index')),
 *         // 'Products' menu item will be selected no matter which tag parameter value is since it's not specified.
 *         array('label'=>'Products', 'url'=>array('product/index'), 'items'=>array(
 *             array('label'=>'New Arrivals', 'url'=>array('product/new', 'tag'=>'new')),
 *             array('label'=>'Most Popular', 'url'=>array('product/index', 'tag'=>'popular')),
 *         )),
 *         array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
 *     ),
 * ));
 * </pre>
 *
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package zii.widgets
 * @since 1.1
 */
Yii::import('zii.widgets.CMenu');
class Menu extends CMenu
{

	/**
	 * @var boolean whether the labels for menu items should be HTML-encoded. Defaults to true.
	 */
	public $encodeLabel=false;

	/**
	 * @var array HTML attributes for the menu's root container tag
	 */
	public $htmlOptions=array('class'=>'nav navbar-nav ');
	/**
	 * @var array HTML attributes for the submenu's container tag.
	 */
	public $submenuHtmlOptions=array('class'=>'dropdown-menu','role'=>'menu');

	/**
	 * Recursively renders the menu items.
	 * @param array $items the menu items to be rendered recursively
	 */
	protected function renderMenuRecursive($items)
	{
		$count=0;
		$n=count($items);
		foreach($items as $item)
		{
			$count++;
			$options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
			$class=array();
			if($item['active'] && $this->activeCssClass!='')
				$class[]=$this->activeCssClass;
			if($count===1 && $this->firstItemCssClass!==null)
				$class[]=$this->firstItemCssClass;
			if($count===$n && $this->lastItemCssClass!==null)
				$class[]=$this->lastItemCssClass;
			if($this->itemCssClass!==null)
				$class[]=$this->itemCssClass;
            if(isset($item['items']) && count($item['items']))
                $class[]='dropdown';
			if($class!==array())
			{
				if(empty($options['class']))
					$options['class']=implode(' ',$class);
				else
					$options['class'].=' '.implode(' ',$class);
			}




			echo CHtml::openTag('li', $options);

			$menu=$this->renderMenuItem($item);
			if(isset($this->itemTemplate) || isset($item['template']))
			{
				$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
				echo strtr($template,array('{menu}'=>$menu));
			}
			else
				echo $menu;

			if(isset($item['items']) && count($item['items']))
			{
				echo "\n".CHtml::openTag('ul',isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions)."\n";
				$this->renderMenuRecursive($item['items']);
				echo CHtml::closeTag('ul')."\n";
			}

			echo CHtml::closeTag('li')."\n";
		}
	}

	/**
	 * Renders the content of a menu item.
	 * Note that the container and the sub-menus are not rendered here.
	 * @param array $item the menu item to be rendered. Please see {@link items} on what data might be in the item.
	 * @return string
	 * @since 1.1.6
	 */
	protected function renderMenuItem($item)
	{	
		if(isset($item['url']))
		{
            $linkOptions = isset($item['linkOptions']) ? $item['linkOptions'] : array();
            if(isset($item['title']))
                $linkOptions['title']   = $item['title'];
			$label=$this->linkLabelWrapper===null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
            if(isset($item['items']) && count($item['items']))
            {
                $label .=CHtml::tag('span',array('class'=>'caret'));
                $linkOptions['class'] ='dropdown-toggle';
                $linkOptions['data-toggle'] ='dropdown';
            }

            return CHtml::link($label,$item['url'],$linkOptions);
		}
		else
			return CHtml::tag('span',isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
	}
}