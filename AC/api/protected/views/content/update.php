<?php
$this->breadcrumbs=array(
	'Contents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
//	array('label'=>'List Content', 'url'=>array('index')),
//	array('label'=>'Create Content', 'url'=>array('create')),
	array('label'=>'View Content', 'url'=>array('view', 'id'=>$model->id)),
//	array('label'=>'Manage Content', 'url'=>array('admin')),
);
?>

<h4>Update Content <?php echo $model->code; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>