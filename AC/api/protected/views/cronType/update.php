<?php
$this->breadcrumbs=array(
	'Cron Types'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CronType', 'url'=>array('index')),
	array('label'=>'Create CronType', 'url'=>array('create')),
	array('label'=>'View CronType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CronType', 'url'=>array('admin')),
);
?>

<h1>Update CronType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>