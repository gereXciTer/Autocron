<?php
$this->breadcrumbs=array(
	'Crons',
);

$this->menu=array(
	array('label'=>'Create Cron', 'url'=>array('create')),
	array('label'=>'Manage Cron', 'url'=>array('admin')),
);
?>

<h1>Crons</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
