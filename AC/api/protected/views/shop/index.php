<?php
$this->breadcrumbs=array(
	'Shops',
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Shop'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Shop'), 'url'=>array('admin'), 'visible'=>Yii::app()->user->name=="admin"),
);
?>

<h4><?php echo Yii::t('app','Shops'); ?></h4>

<?php 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'enableSorting'=>false,
	'enablePagination'=>false,
	'separator'=>'',
	'summaryText'=>'',
	'template'=>'{items}',
	'itemsTagName'=>'ul data-role="listview" data-inset="true" data-split-icon="gear" data-split-theme="b"',
));
?>
