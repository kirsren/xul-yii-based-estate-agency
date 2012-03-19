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
            'firstxul'=>$this->createAbsoluteUrl('/estateagency'),
            'secondxul'=>$this->createAbsoluteUrl('/user/user/login'),
        );
        echo CJSON::encode($ret);
    }
}