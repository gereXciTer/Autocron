<div class="form" data-role="fieldcontain">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'expense-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php if(!empty($cars) && !isset($_GET['car_id'])){ ?>
			<?php echo $form->labelEx($model,'car_id'); ?>
			<?php $cars = CHtml::listData($cars, 'id', 'name'); ?>
			<?php echo $form->dropDownList($model,'car_id',$cars); ?>
			<?php echo $form->error($model,'car_id'); ?>
		<?php } ?>
		
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php $categories = CHtml::listData($categories, 'id', 'name'); ?>
		<?php echo $form->dropDownList($model,'category_id',$categories); ?>
		<?php echo $form->error($model,'category_id'); ?>

		<?php 
			if($user->isPremium()){
				echo CHtml::link(Yii::t('app','Add Category'), array('expenseCategory/create', 'expense_id'=>$model->id), array('data-role'=>'button'));
			}
		?>

		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('class'=>'scroller')); ?>
		<?php echo $form->error($model,'time'); ?>

		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>

		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>

		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
		<?php echo $form->error($model,'value'); ?>

<?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'currency'); ?>
		<?php echo $form->textField($model,'currency'); ?>
		<?php echo $form->error($model,'currency'); ?>
	</div>
*/ ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->