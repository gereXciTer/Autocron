<div class="form" data-role="fieldcontain">
<script>
$(document).ready(function(){
	$('#Cron_edit_current').change(function(){
		$('#additional-props').slideToggle();
		var last_update = $('#Cron_last_update_was').val();
		$('#Cron_last_update_was').val($('#Cron_last_update').val());
		$('#Cron_last_update').val(last_update).scroller();
	});
});
</script>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cron-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-ajax'=>"false",
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php 
		if(!empty($types) || !$model->isNewRecord){
			
			if($model->isNewRecord){
				$data = CHtml::listData($types,'id','name');
				echo $form->labelEx($model,'type');
				echo $form->dropDownList($model,'type',$data,array('prompt'=>Yii::t('app','Choose type'),'class'=>'cron-types','actionUrl'=>Yii::app()->createUrl('cron/getDefaults',array('car_id'=>$_GET['car_id']))));
				echo $form->error($model,'type');

				echo CHtml::link(Yii::t('app','Add reminder type'), array('cronType/create', 'car_id'=>$_GET['car_id']), array('data-role'=>'button'));
			}
		?>
		<?php echo CHtml::label($model->getAttributeLabel('mileage').' ('.$userCar->getMileageType().')','',array('class'=>'ui-input-text')); ?>
		<?php echo $form->textField($model,'mileage'); ?>
		<?php echo $form->error($model,'mileage'); ?>

		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
		<?php echo $form->error($model,'value'); ?>

		<?php echo $form->labelEx($model,'last_update'); ?>
		<?php
			echo $form->textField($model,'last_update',array('class'=>'scroller', 'value'=>date('Y-m-d H:i:s')));
			if(!$model->isNewRecord){
				echo $form->hiddenField($model,'last_update_was',array('value'=>$model->last_update));
			}
		?>
		<?php echo $form->error($model,'last_update'); ?>

	<?php if(!$model->isNewRecord){ ?>
		<?php echo $form->labelEx($model,'edit_current'); ?>
		<?php echo $form->checkBox($model,'edit_current', array('value'=>0, 'uncheckValue'=>NULL)); ?>
		<?php echo $form->error($model,'edit_current'); ?>
	<?php } ?>
	<div id="additional-props" <?php if(!$model->isNewRecord) echo 'style="display:none;"'; ?>>
		<?php echo $form->labelEx($model,'user_period'); ?>
		<?php echo $form->textField($model,'user_period'); ?>
		<?php echo $form->error($model,'user_period'); ?>

		<?php echo $form->labelEx($model,'user_mileage'); ?>
		<?php echo $form->textField($model,'user_mileage'); ?>
		<?php echo $form->error($model,'user_mileage'); ?>

	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Update'), array('data-ajax'=>'false')); ?>
	</div>
		<?php }else{ 
			echo CHtml::link(Yii::t('app','Add reminder type'), array('cronType/create'), array('data-role'=>'button'));
		} ?>

<?php $this->endWidget(); ?>

</div><!-- form -->