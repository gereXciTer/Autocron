<h4><?php  ?></h4>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-car-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
		'data-ajax'=>'false',
	),
)); ?>

	<?php echo $form->errorSummary($definition); ?>

	<?php 
	if(isset($_GET['item']) && $_GET['item'] == 'brand'){
		echo '<h4>'.Yii::t('app','Add supported brand: ').'</h4>';
		echo $form->labelEx($definition,'brand_id');
		$brands = CHtml::listData($brands, 'id', 'name');
		echo $form->dropDownList($definition,'brand_id', $brands);

		echo $form->labelEx($definition,'all_brands');
		echo $form->checkBox($definition,'all_brands', array('uncheckValue'=>NULL));
	}

	if(isset($_GET['item']) && $_GET['item'] == 'cron_type'){
		echo '<h4>'.Yii::t('app','Add service: ').'</h4>';
		echo $form->labelEx($definition,'cron_type_id');
		$cron_types = CHtml::listData($cron_types, 'id', 'name');
		echo $form->dropDownList($definition,'cron_type_id', $cron_types);
	}
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($definition->isNewRecord ? Yii::t('app',Yii::t('app','Add')) : Yii::t('app',Yii::t('app','Save'))); ?>
	</div>

<?php $this->endWidget(); ?>
