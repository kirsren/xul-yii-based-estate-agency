<?php
$address = $model->city . ', '.$model->address;

Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps/api/js?sensor=false');
Yii::app()->clientScript->registerScript('map',
<<<MAP

var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });   

   geocoder = new google.maps.Geocoder();
   geocoder.geocode( { 'address': '$address'}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map, 
            position: results[0].geometry.location,
        });
           var infowindow = new google.maps.InfoWindow({
                content: '$address'
            });
           infowindow.open(map, marker);

      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
MAP
, XulClientScript::POS_LOAD);

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
<x:hbox flex="1">
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
            'value'=> "<a onclick=\"window.openDialog('"
                . CHtml::encode($this->createAbsoluteUrl('/estateagency/estate/viewimage', array('id'=>$model->id)))
                ."', 'image', 'modal');\" >". CHtml::image($model->getFileUrl(),'Image'). "</a>"
        ),
	),
)); 

?>


<div id="map" style="width:300px; height: 300px;"></div>


</x:hbox>
