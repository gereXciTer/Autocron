<?php
$this->breadcrumbs=array(
	'User Cars'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
//	array('label'=>Yii::t('app','View car'), 'url'=>array('view', 'id'=>$model->id)),
//	array('label'=>Yii::t('app','Delete car'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
/*
	array('label'=>'List UserCar', 'url'=>array('index')),
	array('label'=>'Create UserCar', 'url'=>array('create')),
	array('label'=>'View UserCar', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserCar', 'url'=>array('admin')),
*/
);
?>

<h4><?php echo Yii::t('app','Updating: ').$model->name; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>