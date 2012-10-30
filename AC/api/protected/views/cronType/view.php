<?php
$this->breadcrumbs=array(
	'Cron Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List CronType', 'url'=>array('index')),
	array('label'=>'Create CronType', 'url'=>array('create')),
	array('label'=>'Update CronType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CronType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CronType', 'url'=>array('admin')),
);
?>

<h1>View CronType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'period',
		'mileage',
		'primary',
	),
)); ?>
