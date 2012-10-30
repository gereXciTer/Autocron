<?php
$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<h4><?php echo Yii::t('app', 'About'); ?></h4>

<?php 
	echo '<div class="introduction">';
	echo Content::getStatic('about');
	echo '</div>';
?>
<p><?php echo '&copy; '.date('Y').' '.Yii::t('app', 'eXciTer. All rights reserved.'); ?></p>