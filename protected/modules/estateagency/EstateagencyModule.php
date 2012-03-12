<?php

class EstateagencyModule extends CWebModule
{
    
    public $defaultController = 'default';
        
    private $_assetsUrl;
     
	public function init()
	{        
		// import the module-level models and components
		$this->setImport(array(
			'estateagency.models.*',
			'estateagency.components.*',
		));
        
        Yii::app()->name = 'Estate Agency';
        $this->layoutPath = Yii::getPathOfAlias('estateagency.views.layouts');
        
	}
    
    public function getAssetsUrl()
    {
        
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('application.modules.estateagency.assets'), false, '-1', YII_DEBUG );
        return $this->_assetsUrl;
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
