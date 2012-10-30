<?php

echo CHtml::link(Yii::t('app', 'Edit content'), array('content/index'), array('data-role'=>'button', 'data-ajax'=>'false'));

echo CHtml::link(Yii::t('app', 'Manage coupons'), array('coupon/admin'), array('data-role'=>'button', 'data-ajax'=>'false'));


?>