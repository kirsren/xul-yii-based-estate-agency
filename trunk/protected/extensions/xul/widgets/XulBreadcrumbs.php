<?php
/**
 * CBreadcrumbs class file.
 */
class XulBreadcrumbs extends CBreadCrumbs
{
	/**
	 * @var string the tag name for the breadcrumbs container tag. Defaults to 'div'.
	 */
	public $tagName='hbox';

	public function run()
	{
		if(empty($this->links))
			return;

		echo Xul::openTag($this->tagName,$this->htmlOptions)."\n";
		$links=array();
		if($this->homeLink===null)
			$links[]=Xul::link(Yii::t('xul','Home'),Yii::app()->homeUrl);
		else if($this->homeLink!==false)
			$links[]=$this->homeLink;
		foreach($this->links as $label=>$url)
		{
			if(is_string($label) || is_array($url))
				$links[]=CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
			else
				$links[]=Xul::xtag('description',null,$this->encodeLabel ? CHtml::encode($url) : $url);
		}
		echo implode($this->separator,$links);
		echo Xul::closeTag($this->tagName);
	}
}