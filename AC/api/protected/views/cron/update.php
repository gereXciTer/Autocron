<?php
$this->breadcrumbs=array(
	'Crons'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
//	array('label'=>'List Cron', 'url'=>array('index')),
//	array('label'=>'Create Cron', 'url'=>array('create')),
//	array('label'=>'View Cron', 'url'=>array('view', 'id'=>$model->id)),
//	array('label'=>'Manage Cron', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','Edit reminder: '); ?></h4>

<?php echo $this->renderPartial('_form', array(
			'model'=>$model,
			'userCar'=>$userCar,
			'types'=>$types,
)); ?>