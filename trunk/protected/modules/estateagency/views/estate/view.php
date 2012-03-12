<?php
$this->breadcrumbs=array(
	'Estates'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Estate', 'url'=>array('index')),
	array('label'=>'Create Estate', 'url'=>array('create')),
	array('label'=>'Update Estate', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Estate', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Estate', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'price',
		'rooms',
		'heating',
		'type',
		'city',
		'address',
		array(
            'label'=>'Agent',
            'value'=>YumUser::model()->findByPk($model->agent)->username
        ),
	   array(
            'label'=>'Client',
            'value'=>Client::model()->findByPk($model->client)->name
        ),
        array(
            'label' => 'Image',
            'type'=>'raw',
            'value'=> CHtml::link(CHtml::image($model->getFileUrl(),'Image'), $model->getFileUrl('large')),
        ),
	),
)); 

?>
