<?php
$this->pageTitle='Login';
$this->breadcrumbs=array(
	'Login',
);
?>


<div class="form" data-role="fieldcontain">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array(
		'data-ajax'=>'false',
	),
)); ?>

		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>

		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
<? /*
		<fieldset data-role="controlgroup" data-type="horizontal">
		<?php echo $form->label($model,'rememberMe',array('data-theme'=>"a")); ?>
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php if($model->rememberMe) echo 'Checked';?>
		</fieldset>
*/?>
		<?php echo CHtml::submitButton(Yii::t('app','Login'));
		
		echo '<h4>'.Yii::t('app', 'Login with').':</h4>
		<div class="social-buttons">';
			$link = array(
				'scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile',
				'response_type=code',
				'redirect_uri=http://autocron.ru/?r=site/oauthlogin',
				'state=site/index',
				'client_id=412522005139.apps.googleusercontent.com',
			);
			$url = 'https://accounts.google.com/o/oauth2/auth?'.implode('&',$link);
			echo CHtml::link('<img src="images/google-48x48.png" />', $oAuthUrl, array('data-ajax'=>'false', 'class'=>'social-login-btn'));
			
			echo CHtml::link('<img src="images/facebook-48x48.png" />', 'https://www.facebook.com/dialog/oauth?scope=email&redirect_uri=http://autocron.ru/index.php&client_id=207855105987264', array('data-ajax'=>'false', 'class'=>'social-login-btn'));
		echo '</div>';
		?>
	  

<?php $this->endWidget(); 
	echo '<h4>'.Yii::t('app', 'Forgot password?').'</h4>';
	echo CHtml::link(Yii::t('app','Reset password'), array('/site/passRecovery'), array('data-role'=>'button', 'data-ajax'=>'false'));
	echo '<h4>'.Yii::t('app', 'Don\'t have account?').'</h4>';
	echo CHtml::link(Yii::t('app','User Registration'), array('/site/register'), array('data-role'=>'button'));
	echo CHtml::link(Yii::t('app','Seller Registration'), array('/site/register', 'seller'=>1), array('data-role'=>'button'));
?>
</div><!-- form -->
