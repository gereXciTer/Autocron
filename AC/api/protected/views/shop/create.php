<?php
$this->breadcrumbs=array(
	'Shops'=>array('index'),
	'Create',
);

$this->menu=array(
//	array('label'=>'List Shop', 'url'=>array('index')),
	array('label'=>'Manage Shop', 'url'=>array('admin'), 'visible'=>Yii::app()->user->name=="admin"),
);
?>
<h4><?php echo Yii::t('app','Create Shop'); ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model,'user'=>$user)); ?>

