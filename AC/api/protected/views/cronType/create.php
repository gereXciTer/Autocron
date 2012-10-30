<?php
$this->breadcrumbs=array(
	'Cron Types'=>array('index'),
	'Create',
);

$this->menu=array(
//	array('label'=>'List CronType', 'url'=>array('index')),
//	array('label'=>'Manage CronType', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','New reminder type: '); ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>