<?php
/**
 * XLinkPager class file.
 *
 * @author Zsolt Lengyl <zsolt.lengyel.it@gmail.com>
 */

/**
 * XLinkPager displays a list of links that lead to different pages of target.
 */
class XulLinkPager extends CLinkPager
{
	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		$this->registerClientScript();
		$buttons=$this->createPageButtons();
		if(empty($buttons))
			return;
		echo $this->header;
		echo Xul::xtag('hbox',$this->htmlOptions,implode("\n",$buttons));
		echo $this->footer;
	}

	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 * @param string $label the text label for the button
	 * @param integer $page the page number
	 * @param string $class the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
	 * @param boolean $hidden whether this page button is visible
	 * @param boolean $selected whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected)
			$class.=' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
        return Xul::link($label,$this->createPageUrl($page), array('class'=>$class));
	}

	/**
	 * Registers the needed CSS file.
	 * @param string $url the CSS URL. If null, a default CSS URL will be used.
	 * @since 1.0.2
	 */
	public static function registerCssFile($url=null)
	{
		if($url===null)
			$url=CHtml::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager').'.css');
		Yii::app()->getClientScript()->registerCssFile($url);
	}
}
