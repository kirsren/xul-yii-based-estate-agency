<?php

class DefaultController extends EAController
{
    public $layout = 'main';
    
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
    
    public function actionMain()
	{
        $this->layout = 'embededWindow';
		$this->render('index');
	}
    
    public function actionTestdialog(){
        $this->layout = 'dialog';
        $this->render('index');
    }
    
    public function actionInitxul(){
        $ret = array(
        'appName' => Yii::app()->name,
        'mainWindow'=>'first.xul',
        'files' => array(
            'first.xul'=>$this->createAbsoluteUrl('/estateagency'),
            'second.xul'=>$this->createAbsoluteUrl('/user/user/login'),
            'third.xul'=>'http://localhost/szakdoga/yii/index.php?r=estateagency/estate/view&id=1',
            )
        );
        echo CJSON::encode($ret);
    }
}