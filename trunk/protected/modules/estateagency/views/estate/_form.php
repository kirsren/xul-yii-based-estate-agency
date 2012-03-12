<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'estate-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php  echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php  echo $form->textField($model,'price');
        //echo Xul::activeNumberfield($model, 'price', array('min'=>1, 'max'=>'1000000')); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rooms'); ?>
		<?php echo $form->textField($model,'rooms'); ?>
		<?php echo $form->error($model,'rooms'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'heating'); ?>
		<?php echo $form->textField($model,'heating',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'heating'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image'); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'agent'); ?>
		<?php        
         echo $form->dropDownList($model,'agent',
             CHtml::listData(YumUser::model()->getByRole('Agent')
             , 'id', 'username')            
        ); ?>
		<?php echo $form->error($model,'agent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'client'); ?>
		<?php
         echo $form->dropDownList($model,'client',
             CHtml::listData(Client::model()->findAll('agent=:agent', array(':agent'=>Yii::app()->user->id))
             , 'id', 'name')            
        ); ?>
		<?php echo $form->error($model,'agent'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->