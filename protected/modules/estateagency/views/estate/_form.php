<?php

echo Xul::openVbox();

$form=$this->beginWidget('ext.xul.widgets.form.XulActiveForm', array(
	'id'=>'estate-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
));


	echo $form->errorSummary($model);

	echo Xul::openVbox();
		echo $form->labelEx($model,'name'); 
		echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); 
		echo $form->error($model,'name'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'description'); 
		 echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); 
		echo $form->error($model,'description'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'price'); 
		 echo $form->textField($model,'price');
        //echo Xul::activeNumberfield($model, 'price', array('min'=>1, 'max'=>'1000000')); 
		echo $form->error($model,'price'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'rooms'); 
		echo $form->textField($model,'rooms'); 
		echo $form->error($model,'rooms'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'heating'); 
		echo $form->textField($model,'heating',array('size'=>20,'maxlength'=>20)); 
		echo $form->error($model,'heating'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'type'); 
		echo $form->textField($model,'type',array('size'=>10,'maxlength'=>10)); 
		echo $form->error($model,'type'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'city'); 
		echo $form->textField($model,'city',array('size'=>60,'maxlength'=>255)); 
		echo $form->error($model,'city'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'address'); 
		echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); 
		echo $form->error($model,'address'); 
	echo Xul::closeVbox();
    
    echo Xul::openVbox();
		echo $form->labelEx($model,'image'); 
		echo $form->fileField($model,'image'); 
		echo $form->error($model,'image'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'agent'); 
		       
         echo $form->dropDownList($model,'agent',
             CHtml::listData(YumUser::model()->getByRole('Agent')
             , 'id', 'username')            
        ); 
		echo $form->error($model,'agent'); 
	echo Xul::closeVbox();

	echo Xul::openVbox();
		echo $form->labelEx($model,'client'); 
		
        echo $form->dropDownList($model,'client',
             CHtml::listData(Client::model()->findAll('agent=:agent', array(':agent'=>Yii::app()->user->id))
             , 'id', 'name')            
        ); 
		echo $form->error($model,'agent'); 
	echo Xul::closeVbox();

	echo Xul::openHbox();
		echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');
	echo Xul::closeHbox();

$this->endWidget(); 

echo Xul::closeVbox();

