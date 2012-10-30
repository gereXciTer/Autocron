<?php
$this->breadcrumbs=array(
	'Expenses'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
//	array('label'=>'List Expense', 'url'=>array('index')),
//	array('label'=>'Create Expense', 'url'=>array('create')),
//	array('label'=>'View Expense', 'url'=>array('view', 'id'=>$model->id)),
//	array('label'=>'Manage Expense', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','Update expense'); ?> :</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'categories'=>$categories, 'cars'=>$cars, 'user'=>$user)); ?>