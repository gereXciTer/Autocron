<?php
$this->breadcrumbs=array(
	'User Cars',
);

$this->menu=array(
//	array('label'=>'Create UserCar', 'url'=>array('create')),
//	array('label'=>'Manage UserCar', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','Your cars');?>:</h4>

<?php 
	if(!empty($userCars))
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$userCars,
		'itemView'=>'_view',
		'enableSorting'=>false,
		'enablePagination'=>false,
		'separator'=>'',
		'summaryText'=>'',
		'template'=>'{items}',
		'itemsTagName'=>'ul data-role="listview" data-inset="true" data-split-icon="gear" data-split-theme="a"',
		'htmlOptions'=>array(
			'data-theme'=>'c',
		),
	));
	echo '<div style="clear:both">'.CHtml::link(Yii::t('app','Add car'), array('userCar/create'), array('data-role'=>'button', 'data-ajax'=>'false')).'</div>';
 ?>
