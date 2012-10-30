<?php
	if(isset($_GET['action']))
		echo Yii::t('app', '<p>To perform this action you need to get premium account.</p>');
		
	echo Yii::t('app', '<p>To update your current account to premium please follow these steps:</p>');
/*

<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
<input type="hidden" name="pay_to_email" value="vladimir.gartsev@gmail.com">
<input type="hidden" name="status_url" value="vladimir.gartsev@gmail.com"> 
<input type="hidden" name="language" value="EN">
<input type="hidden" name="amount" value="5">
<input type="hidden" name="currency" value="USD">
<input type="hidden" name="detail1_description" value="Premium subscription (6 months)">
<input type="hidden" name="detail1_text" value="Premium subscription (6 months)">
<input type="submit" value="Pay">
</form>

*/
	if(stripos($user->location, 'Belarus')||stripos($user->location, 'Russia'||stripos($user->location, 'Ukraine'))){
		
	}
	echo CHtml::link(Yii::t('app','Buy 6 months for just $6'), 'https://www.plimus.com/jsp/buynow.jsp?contractId=3116366&email='.$user->email, array('data-role'=>'button'));
	echo CHtml::link(Yii::t('app','Buy 1 year and save 20%'), 'https://www.plimus.com/jsp/buynow.jsp?contractId=2291993&email='.$user->email, array('data-role'=>'button'));

echo '<h4>'.Yii::t('app', 'Or enter promo-code').'</h4>';
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'coupon-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-ajax'=>"false",
	),
)); ?>

		<?php echo $form->textField($coupon,'code'); ?>
		<?php echo $form->error($coupon,'code'); ?>

		<?php echo CHtml::submitButton(Yii::t('app','Submit')); ?>

<?php $this->endWidget(); ?>
