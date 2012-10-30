<?php
$this->breadcrumbs=array(
	'Expenses'=>array('index'),
	'Create',
);

$this->menu=array(
//	array('label'=>'List Expense', 'url'=>array('index')),
//	array('label'=>'Manage Expense', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','New expense'); ?> :</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'categories'=>$categories, 'cars'=>$cars, 'user'=>$user)); ?>