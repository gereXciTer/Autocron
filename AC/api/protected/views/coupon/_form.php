<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'coupon-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type'); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodie'); ?>
		<?php echo $form->dropDownList($model,'goodie', CHtml::listData(Goodie::model()->findAll('active=1'),'id','value','code'), array('prompt'=>Yii::t('app','Select goodie if needed'))); ?>
		<?php echo $form->error($model,'goodie'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'expired'); ?>
		<?php echo $form->textField($model,'expired', array('class'=>'scroller')); ?>
		<?php echo $form->error($model,'expired'); ?>
	</div>

<?php if(!$model->isNewRecord){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'used'); ?>
		<?php echo $form->textField($model,'used'); ?>
		<?php echo $form->error($model,'used'); ?>
	</div>
<?php } ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->