<?php

class DefaultController extends EAController
{
    public $layout = 'main';
    public $defaultAction = 'download';

    public function layouts()
    {
        return array(
            'browser, main' => 'embededWindow.xul',
            'testdialog' => 'dialog.xul',
            'initxul, extension, download' => '');
    }

    public function accessRules()
    {
        return array(
            
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'index'),
				'users'=>array('@'),
			),
            array(
                'allow',
                'actions' => array(
                    'index',
                    'create',
                    'update',
                    'view',
                    'delete',
                    'testdialog'),
                'roles' => array('agent')),
            array(
                'allow',
                'actions' => array('*'),
                'roles' => array('admin')),
            array(
                'deny', // deny all users
                'actions' => array('*'),
                'users' => array('*'),
                ),

            );
    }

    public function actionIndex()
    {
        
        $estateController = Yii::app()->createController('/estateagency/estate');
        $estateController = $estateController[0];
        $estateController->init();
        $estateController->forcePartialRender = true;        
        
        $this->render('index', array(
            'estateController'=>$estateController        
        ));
    }
    
    public function actionDownload()
    {
        $this->renderPartial('download');
    }    
    

    public function actionBrowser()
    {
        $this->render('browser');
    }

    public function actionBindings()
    {
        $this->renderPartial('bindings');
    }

    public function actionMain()
    {
        $this->render('index');
    }

    public function actionTestdialog()
    {
        $this->render('index');
    }

    public function actionInitxul()
    {
        $ret = array(
            'appName' => Yii::app()->name,
            'mainWindow' => 'first.xul',
            'files' => array(
                'first.xul' => $this->createAbsoluteUrl('/estateagency'),
                'welcome.xul' => $this->createAbsoluteUrl('/estateagency/estate'),
                'bindings/bindings.xbl' => $this->createAbsoluteUrl('/estateagency/default/bindings'),
                'bindings/bindings.css' => $this->module->assetsUrl . '/css/bindings.css',
                'browser.xul' => $this->createAbsoluteUrl('/estateagency/default/browser'),

                'components/autocomplete.js' => $this->module->getAssetsUrl() .
                    '/scripts/autocomplete.js',
                ));
        echo CJSON::encode($ret);
    }

    public function actionExtension($output=false)
    {
        $this->layout = 'main';
        
        ;       
        ob_start();
        $this->run('index');
        $content = ob_get_contents();
        ob_end_clean();
        
        if($output){
            echo $content;
            return;
        }
        
        $mainxulPath = Yii::getPathOfAlias('application.modules.estateagency.addontemplate.chrome.content');
        file_put_contents($mainxulPath . DIRECTORY_SEPARATOR . 'main.xul', $content);

        $templatePath = Yii::getPathOfAlias('application.modules.estateagency.addontemplate') .
            DIRECTORY_SEPARATOR;
        $extPath = Yii::getPathOfAlias('application.modules.estateagency.assets.addon') .
            DIRECTORY_SEPARATOR . 'agency.xpi';

        /*
        $zip = Yii::app()->zip;
        $zip->makeZip($templatePath, $extPath);
        */

        Yii::import('ext.zip.7zip.Zip7');
        $zip = new Zip7();
        $zip->makezip($templatePath, $extPath);
        //echo $this->module->getAssetsUrl().'/addon/agency.xpi';
        //header('Content-type: text/html');
        header('Content-type: application/x-xpinstall');

        readfile($extPath);

    }

}
