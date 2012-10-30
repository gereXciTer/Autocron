<?php
$this->pageTitle = 'Pass recovery';
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-ajax'=>'false',
	),
)); ?>
	<div class="row" data-role="fieldcontain">
	<?php echo $form->errorSummary($user); ?>

		<?php echo $form->labelEx($user,'email'); ?>
		<?php echo $form->textField($user,'email'); ?>
		<?php echo $form->error($user,'email'); ?>
		
		<?php if($enterkey){ ?>

			<?php echo $form->labelEx($user,'verification_key'); ?>
			<?php echo $form->textField($user,'verification_key'); ?>
			<?php echo $form->error($user,'verification_key'); ?>

			<?php echo $form->labelEx($user,'password'); ?>
			<?php echo $form->passwordField($user,'password'); ?>
			<?php echo $form->error($user,'password'); ?>

			<?php echo $form->labelEx($user,'password_repeat'); ?>
			<?php echo $form->passwordField($user,'password_repeat'); ?>
			<?php echo $form->error($user,'password_repeat'); ?>

		<?php } ?>
		
		<?php echo CHtml::submitButton(Yii::t('app','Submit')); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->