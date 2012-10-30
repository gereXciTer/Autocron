<?php
$this->breadcrumbs=array(
	'Cron Types',
);

$this->menu=array(
	array('label'=>'Create CronType', 'url'=>array('create')),
	array('label'=>'Manage CronType', 'url'=>array('admin')),
);
?>

<h1>Cron Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
