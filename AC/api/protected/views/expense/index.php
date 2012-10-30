<?php

$this->breadcrumbs=array(
	'Expenses',
);

$this->menu=array(
//	array('label'=>'Create Expense', 'url'=>array('create')),
//	array('label'=>'Manage Expense', 'url'=>array('admin')),
);

if(isset($_GET['car_id']))
	echo CHtml::link(Yii::t('app','Show charts'), array('expense/charts', 'car_id'=>$_GET['car_id']), array('data-role'=>'button', 'data-ajax'=>'false'));
else
	echo CHtml::link(Yii::t('app','Show charts'), array('expense/charts'), array('data-role'=>'button', 'data-ajax'=>'false'));

?>
<?php if(isset($_GET['car_id']) && User::model()->findByPk(Yii::app()->user->getId())->isPremium()){ ?>
	<h4><?php echo Yii::t('app','Expenses for: ').UserCar::model()->findByPk(addslashes($_GET['car_id']))->name; ?></h4>
	<?php echo CHtml::link(Yii::t('app','Show all'), array('expense/index'), array('data-role'=>'button', 'data-ajax'=>'false')); ?><br />
<?php }else{ ?>
	<h4><?php echo Yii::t('app','Expenses'); ?> :</h4>
<?php } ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'itemsTagName'=>'ul data-role="listview" data-inset="true" data-filter="true" data-theme="c" data-dividertheme="b"',
	'summaryText'=>'',
));

if(isset($_GET['car_id']))
	echo CHtml::link(Yii::t('app','Add expense'), array('expense/create', 'car_id'=>$_GET['car_id']), array('data-role'=>'button', 'data-icon'=>'plus', 'data-ajax'=>'false'));
else
	echo CHtml::link(Yii::t('app','Add expense'), array('expense/create'), array('data-role'=>'button', 'data-icon'=>'plus', 'data-ajax'=>'false'));
	
if($unusedcats) {
	echo CHtml::link(Yii::t('app','Clear unused categories'), array('expenseCategory/clearUnused'), array('data-role'=>'button'));
}
?>

