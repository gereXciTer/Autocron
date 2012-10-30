<?php
$this->breadcrumbs=array(
	'Coupons'=>array('index'),
	'Manage',
);

$this->menu=array(
//	array('label'=>'List Coupon', 'url'=>array('index')),
//	array('label'=>'Create Coupon', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('coupon-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Coupons</h1>
<?php echo CHtml::link('Create coupon',array('coupon/create'), array('data-role'=>'button', 'data-ajax'=>'false')); ?>
<?php echo CHtml::link('Generete many',array('coupon/createMany'), array('data-role'=>'button', 'data-ajax'=>'false')); ?>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'coupon-grid',
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
	'columns'=>array(
//		'id',
		'code',
		'username',
		'type',
		'expired',
		'used',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
