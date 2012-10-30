<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-car-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
		'data-ajax'=>'false',
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image'); ?><br />
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->