<div class="form" data-role="fieldcontain">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-car-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
		'data-ajax'=>'false',
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>

		<?php echo $form->labelEx($model,'mileage_multiplier'); ?>
		<?php echo $form->dropDownList($model,'mileage_multiplier', UserCar::getMileageTypes()); ?>
		<?php echo $form->error($model,'mileage_multiplier'); ?>

		<?php
		if(!$model->mileage_initial){
			echo $form->labelEx($model,'mileage_initial');
			echo $form->textField($model,'mileage_initial');
			echo $form->error($model,'mileage_initial');
		}
		?>

		<?php echo $form->labelEx($model,'mileage'); ?>
		<?php echo $form->textField($model,'mileage'); ?>
		<?php echo $form->error($model,'mileage'); ?><br />

		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image'); ?><br />

		<?php echo $form->labelEx($model,'year_built'); ?>
		<?php echo $form->textField($model,'year_built',array('class'=>'scroller year')); ?>
		<?php echo $form->error($model,'year_built'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->