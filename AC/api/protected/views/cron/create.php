<?php
$this->breadcrumbs=array(
	'Crons'=>array('index'),
	'Create',
);

$this->menu=array(
//	array('label'=>'List Cron', 'url'=>array('index')),
//	array('label'=>'Manage Cron', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','New reminder: '); ?></h4>

<?php echo $this->renderPartial('_form', array(
			'model'=>$model,
			'userCar'=>$userCar,
			'types'=>$types,
)); ?>