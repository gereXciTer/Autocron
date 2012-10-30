<?php
$this->breadcrumbs=array(
	'Cron Histories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CronHistory', 'url'=>array('index')),
	array('label'=>'Create CronHistory', 'url'=>array('create')),
	array('label'=>'Update CronHistory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CronHistory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CronHistory', 'url'=>array('admin')),
);
?>

<h1>View CronHistory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'car_id',
		'type',
		'mileage',
		'value',
		'last_update',
	),
)); ?>
