<?php
$this->breadcrumbs=array(
	'Contents',
);

$this->menu=array(
//	array('label'=>'Create Content', 'url'=>array('create')),
//	array('label'=>'Manage Content', 'url'=>array('admin')),
);
?>

<h4>Content</h4>

<?php
echo CHtml::link(Yii::t('app', 'Add content'), array('content/create'), array('data-role'=>'button', 'data-ajax'=>'false'));

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); 

?>
