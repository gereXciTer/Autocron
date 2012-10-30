<?php
$this->breadcrumbs=array(
	'User Cars'=>array('index'),
	'Create',
);

$this->menu=array(
//	array('label'=>'List UserCar', 'url'=>array('index')),
//	array('label'=>'Manage UserCar', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('app','Add car: '); ?></h4>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-ajax'=>'false',
	),
)); ?>
	<div class="row">
		<?php echo CHtml::dropDownList('car_make','',$car_make,
						array(
							'prompt'=>Yii::t('app','Choose make'),
							'data-native-menu'=>'true',
							'data-theme'=>'c',
							'class'=>'ui-select select-car',
							'actionUrl'=>Yii::app()->createUrl('site/getList'),
						));
		?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','Proceed'), array('disabled'=>'disabled')); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->