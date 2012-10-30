<?php
$this->pageTitle = 'Registration';
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-ajax'=>'false',
	),
)); ?>
<?php if($car_make){ ?>
	<?php if(isset($_GET['seller'])){ ?>
		<?php echo $form->labelEx($user,'role'); ?>
		<?php echo $form->checkBox($user,'role', array('uncheckValue'=>NULL)); ?>
		<?php echo $form->error($user,'role'); ?>

		<?php echo CHtml::hiddenField('no_validation', 1); ?>

		<div data-role="collapsible" data-content-theme="d" class="terms">
		   <h3><?php echo Yii::t('app','Learn more about seller account'); ?></h3>
		   <p><?php echo Yii::t('app','By registering seller account you become able to sell auto parts to other users. Once you share your location in profile settings, other members will see your proposals (you can select which parts type you are selling and for which auto brands).'); ?></p>
		</div>		
		<p><?php echo Yii::t('app','Also, you are able to use application with all available user experience, in this case you can choose your car at this point too. Or you can ignore that and proceed with registration');?>
		</p>
	<?php } ?>

	<div class="row">
		<h5><?php echo Yii::t('app','Please select your car');?></h5>
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
		<?php echo CHtml::submitButton(Yii::t('app','Proceed'), isset($_GET['seller']) ? array() : array('disabled'=>'disabled')); ?>
	</div>
<?php }else{ ?>
	<?php echo $form->errorSummary($user); ?>
<p>
<?php
	if($user->role){
		echo Yii::t('app','Registering seller account.').'<br />';
	}
	if(isset($car_version)){
		echo Yii::t('app','Your car').':<b> '.$car_version->name.' - '.$car_variant->name.'</b><br />'.
				CHtml::link(Yii::t('app','Choose another'), array('site/register', 'newcar'=>1), array('data-role'=>'button'));
	}else{
		echo CHtml::link(Yii::t('app','Choose car'), array('site/register', 'newcar'=>1), array('data-role'=>'button'));
	}
?>
</p>
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($user,'name'); ?>
		<?php echo $form->textField($user,'name', array('value'=>isset($_GET['name'])?$_GET['name']:'')); ?>
		<?php echo $form->error($user,'name'); ?>

		<?php echo $form->labelEx($user,'email'); ?>
		<?php echo $form->textField($user,'email', array('value'=>isset($_GET['email'])?$_GET['email']:'')); ?>
		<?php echo $form->error($user,'email'); ?>

		<?php echo $form->labelEx($user,'password'); ?>
		<?php echo $form->passwordField($user,'password'); ?>
		<?php echo $form->error($user,'password'); ?>

		<?php echo $form->labelEx($user,'password_repeat'); ?>
		<?php echo $form->passwordField($user,'password_repeat'); ?>
		<?php echo $form->error($user,'password_repeat'); ?>

		<?php echo $form->hiddenField($user,'role'); ?>

			<div data-role="collapsible" data-content-theme="d" class="terms">
			   <h3><?php echo Yii::t('app','Show terms'); ?></h3>
			   <p><?php echo Yii::t('app','Terms of use'); ?></p>
			</div>		
		   <?php echo $form->labelEx($user,'agreeterms'); ?>
			<?php echo $form->checkBox($user,'agreeterms'); ?>
			<?php echo $form->error($user,'agreeterms'); ?>
		<?php echo CHtml::submitButton(Yii::t('app','Register'), array('disabled'=>'disabled')); ?>
	</div>
<?php } ?>
<?php $this->endWidget(); ?>
</div><!-- form -->