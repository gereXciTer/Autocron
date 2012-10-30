<?php
$this->breadcrumbs=array(
	'Crons'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cron', 'url'=>array('index')),
	array('label'=>'Create Cron', 'url'=>array('create')),
	array('label'=>'Update Cron', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cron', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cron', 'url'=>array('admin')),
);
?>

<h1>View Cron #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'car_id',
		'type',
		'mileage',
		'value',
		'user_period',
		'user_mileage',
		'last_update',
	),
)); ?>
