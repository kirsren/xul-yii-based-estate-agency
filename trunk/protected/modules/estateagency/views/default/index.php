<?php
   // Yii::app()->clientScript->registerScriptFile('chrome://myapp/content/js/Sys.js'); // path is absolute ...
    Yii::app()->getClientScript()->registerCssFile('bindings/bindings.css');  // ...but we can give it relative

    $deck = $this->beginWidget('ext.xul.widgets.XulDeck', array(
        'id'=>'content-deck',
        'wrapper'=>'box',
        'xulOptions'=>array('flex'=>1),
        'wrapperOptions'=>array('flex'=>1)     
    ));
    
        $deck->beginWrap('vbox',array('flex'=>1));
            $estateController->run('index');
        $deck->endWrap();
    
    $this->endWidget();
    
?>