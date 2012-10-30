<div class="form" data-role="fieldcontain">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cron-type-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>

		<?php echo $form->labelEx($model,'name_ru'); ?>
		<?php echo $form->textField($model,'name_ru',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name_ru'); ?>

		<?php echo CHtml::label($model->getAttributeLabel('period').' ('.Yii::t('app','In months').')','',array('class'=>'ui-input-text')); ?>
		<?php echo $form->textField($model,'period'); ?>
		<?php echo $form->error($model,'period'); ?>

		<?php echo $form->labelEx($model,'mileage'); ?>
		<?php echo $form->textField($model,'mileage'); ?>
		<?php echo $form->error($model,'mileage'); ?>

		<?php echo $form->labelEx($model,'mileage_multiplier'); ?>
		<?php echo $form->dropDownList($model,'mileage_multiplier', UserCar::getMileageTypes()); ?>
		<?php echo $form->error($model,'mileage_multiplier'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->