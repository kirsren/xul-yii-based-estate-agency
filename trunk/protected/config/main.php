<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__file__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Estate Agency',

    // preloading 'log' component
    'preload' => array(),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        ),

    'modules' => array(
        'user' => array(
            'modules' => array(
                'role',
                'profiles',
                'messages',
                ),
            'debug' => false,
            ),

        'estateagency',

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'password',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            ),

        ),

    // application components
    'components' => array(
        'zip' => array('class' => 'ext.zip.EZip'),
        'user' => array(
            'class' => 'application.modules.user.components.YumWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/user/login'),
            ),

        // uncomment the following to enable URLs in path-format
        /*
        'urlManager'=>array(
        'urlFormat'=>'path',
        'rules'=>array(
        'welcome'=>array('/estateagency/default/main', 'urlSuffix'=>'.xul', 'caseSensitive'=>false),
        '<controller:\w+>/<id:\d+>'=>'<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',                
        ),
        ),
        */
        /*
        'db'=>array(
        'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/test.db',
        ),*/
        // uncomment the following to use a MySQL database
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(array('class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                        // Access is restricted by default to the localhost
                        //'ipFilters'=>array('127.0.0.1','192.168.1.*', 88.23.23.0/24),
                    ), ),
            ),

        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=szakdoga_test',
            'emulatePrepare' => true,
            'username' => 'szakdoga',
            'password' => 'szakdoga',
            'charset' => 'utf8',

            'enableProfiling' => true,
            'enableParamLogging' => true,
            ),

        'errorHandler' => array( // use 'site/error' action to display errors
                'errorAction' => 'site/error', ),
        ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array( // this is used in contact page
            'adminEmail' => 'webmaster@example.com', ),
    );
