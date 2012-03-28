<?php
$this->breadcrumbs=array(
	'Estates'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Estate', 'url'=>array('index')),
	array('label'=>'Create Estate', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('estate-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Estates</h1>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'estate-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(

		'name',
		'description',
		'price',


		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
