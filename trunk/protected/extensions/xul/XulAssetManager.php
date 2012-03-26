<?php

class XulAssetManager extends CAssetManager
{
	/**
	 * Default web accessible base path for storing private files
	 */
	const DEFAULT_BASEPATH='assets';
    
    public $server = '';

	/**
	 * @var string base web accessible path for storing private files
	 */
	private $_basePath;
	/**
	 * @var string base URL for accessing the publishing directory.
	 */
	private $_baseUrl;
	/**
	 * @var array published assets
	 */
	private $_published=array();


	/**
	 * @return string the base url that the published asset files can be accessed.
	 * Note, the ending slashes are stripped off. Defaults to '/AppBaseUrl/assets'.
	 */
	public function getBaseUrl()
	{
		if($this->_baseUrl===null)
		{
			$this->setBaseUrl($this->server . Yii::app()->baseUrl.'/'.self::DEFAULT_BASEPATH);
		}
		return $this->_baseUrl;
	}

	/**
	 * @param string $value the base url that the published asset files can be accessed
	 */
	public function setBaseUrl($value)
	{
		$this->_baseUrl=$value;
	}

}
