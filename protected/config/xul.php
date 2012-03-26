<?php


return CMap::mergeArray(
  require(dirname(__FILE__).'/main.php'),
  array(
    'modules'=>array('test'),
    'defaultController'=> 'estateagency',
    'import'=>array(
      'application.extensions.xul.*'
    ),
    'components'=>array(
      'viewRenderer'=>array(
        'class'=> 'application.extensions.xul.XulViewRenderer',
      ), 
     'clientScript'=>array(
		'class'=> 'application.extensions.xul.XulClientScript',
        ),
      'assetManager'=>array(
        'class'=>'application.extensions.xul.XulAssetManager',
        'server'=>'http://localhost',
      )
    )
    
  )
  );