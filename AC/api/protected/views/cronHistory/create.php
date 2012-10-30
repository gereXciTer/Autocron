<?php
$this->breadcrumbs=array(
	'Cron Histories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CronHistory', 'url'=>array('index')),
	array('label'=>'Manage CronHistory', 'url'=>array('admin')),
);
?>

<h1>Create CronHistory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>