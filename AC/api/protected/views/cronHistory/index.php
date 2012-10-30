<?php
$this->breadcrumbs=array(
	'Cron Histories',
);

$this->menu=array(
//	array('label'=>'Create CronHistory', 'url'=>array('create')),
//	array('label'=>'Manage CronHistory', 'url'=>array('admin')),
);
?>
<?php if(isset($_GET['car_id']) && User::model()->findByPk(Yii::app()->user->getId())->isPremium()){ ?>
	<h4><?php echo Yii::t('app','Service History for: ').CHtml::link(UserCar::model()->findByPk(addslashes($_GET['car_id']))->name, array('userCar/view', 'id'=>addslashes($_GET['car_id'])), array('data-ajax'=>'false')); ?></h4>
	<?php echo CHtml::link(Yii::t('app','Show all'), array('cronHistory/index'), array('data-role'=>'button')); ?><br />
<?php }else{ ?>
	<h4><?php echo Yii::t('app','Service History'); ?></h4>
<?php } ?>

<?php 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$history,
	'itemView'=>'_view',
	'itemsTagName'=>'ul data-role="listview" data-inset="true" data-filter="true" data-theme="c" data-dividertheme="b"',
	'summaryText'=>'',
	'viewData'=>array(
		'one_car'=>$_GET['car_id'],
	),
));
?>
