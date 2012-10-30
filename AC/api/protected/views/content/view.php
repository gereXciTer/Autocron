<?php
$this->breadcrumbs=array(
	'Contents'=>array('index'),
	$model->id,
);

$this->menu=array(
//	array('label'=>'List Content', 'url'=>array('index')),
//	array('label'=>'Create Content', 'url'=>array('create')),
	array('label'=>'Update Content', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Content', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage Content', 'url'=>array('admin')),
);
?>

<h4>View Content #<?php echo $model->code; ?></h4>

<?php 

echo '<p>Language: '.$model->lang.'</p>';
echo '<div>'.$model->text.'</div>';

 ?>
