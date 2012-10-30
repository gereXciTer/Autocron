<?php
$this->breadcrumbs=array(
	'Cron Histories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CronHistory', 'url'=>array('index')),
	array('label'=>'Create CronHistory', 'url'=>array('create')),
	array('label'=>'View CronHistory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CronHistory', 'url'=>array('admin')),
);
?>

<h1>Update CronHistory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>