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
				'actions'=>array('create','update','view','delete'),
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
}