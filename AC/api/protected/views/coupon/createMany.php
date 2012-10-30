
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'coupon-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'goodie'); ?>
		<?php echo $form->dropDownList($model,'goodie', CHtml::listData(Goodie::model()->findAll('active=1'),'id','value','code'), array('prompt'=>Yii::t('app','Select goodie if needed'))); ?>
		<?php echo $form->error($model,'goodie'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type', array('value'=>6)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'count'); ?>
		<?php echo $form->textField($model,'count', array('value'=>10)); ?>
		<?php echo $form->error($model,'count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

<?php

foreach($codes as $key=>$value){
	echo $value.'<br />';
}
?>