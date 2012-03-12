<?php

class DefaultController extends XulController{
  
  public $layout = 'main';
  
  public function actionIndex(){
    
    Yii::import('application.modules.test.models.*');
    
    $this->render('index',array(
      'dataProvider'=> new CActiveDataProvider('Item'),
    ));
    
  }
  
  public function actionShowcase(){
    
    $this->render('showcase');
    
  }
  
}