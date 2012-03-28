<?php

class DefaultController extends EAController
{
    public $layout = 'main';
    
    public function layouts(){
        return array(
            'browser, main'=>'embededWindow.xul',
            'testdialog'=>'dialog.xul',
        );
    }
    
    public function accessRules()
	{
		return array(
			array('deny',  // deny all users
                'actions'=>array('*'),
				'users'=>array('*'),
			),
            array('allow', 
				'actions'=>array('create','update','view','delete', 'testdialog'),
				'roles'=>array('agent')
			),
            array('allow',
                'actions'=>array('*'),
                'roles'=>array('admin')
            ),
			
		);
	}
    
	public function actionIndex()
	{
		$this->render('index');
	}
    
    public function actionBrowser(){
        $this->render('browser');
    }
    
    public function actionBindings(){
        $this->renderPartial('bindings');
    }
    
    public function actionMain()
	{
		$this->render('index');
	}
    
    public function actionTestdialog(){
        $this->render('index');
    }
    
    public function actionInitxul(){
        $ret = array(
        'appName' => Yii::app()->name,
        'mainWindow'=>'first.xul',
        'files' => array(
            'first.xul'=>$this->createAbsoluteUrl('/estateagency'),
            'welcome.xul'=>$this->createAbsoluteUrl('/estateagency/default/main'),
            'bindings/bindings.xbl'=> $this->createAbsoluteUrl('/estateagency/default/bindings'),
            'bindings/bindings.css'=> $this->module->assetsUrl . '/css/bindings.css',
            'browser.xul' => $this->createAbsoluteUrl('/estateagency/default/browser'),
            )
        );
        echo CJSON::encode($ret);
    }
}