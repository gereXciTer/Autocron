<?php
$this->breadcrumbs=array(
	'User Cars'=>array('index'),
	$model->name,
);

$this->menu=array(
//	array('label'=>'List UserCar', 'url'=>array('index')),
//	array('label'=>'Create UserCar', 'url'=>array('create')),
//	array('label'=>Yii::t('app','Add reminder'), 'url'=>array('cron/create', 'car_id'=>$model->id), 'linkOptions'=>array('data-ajax'=>"false")),
	array('label'=>Yii::t('app','Update car'), 'url'=>array('update', 'id'=>$model->id), 'linkOptions'=>array('data-ajax'=>"false")),
	array('label'=>Yii::t('app','Delete car'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage UserCar', 'url'=>array('admin')),
);
?>


<a href="<?php echo Yii::app()->createUrl('userCar/viewimage', array('id'=>$model->id));?>"><img src="<?php echo $model->getImageTnUrl();?>" class="car_icon" /></a>
<h4><?php echo $model->name; ?></h4>

<div class="detail-view ui-grid-a ui-bar-c" data-theme="a" style="clear: both;">
<?php
if(count($model->getReminders())){
	echo '<div class="ui-block reminders">'.implode('<br />', $model->getReminders()).'</div>';
}
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'car_id').'</strong></div>
		<div class="ui-block-b">'.CarModelVersion::model()->findByPk($model->car_id)->name.'</div>';
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'car_variant').'</strong></div>
		<div class="ui-block-b">'.CarModelVariant::model()->findByPk($model->car_variant)->name.'</div>';
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'name').'</strong></div>
		<div class="ui-block-b">'.$model->name.'</div>';
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'date_added').'</strong></div>
		<div class="ui-block-b">'.$model->date_added.'</div>';
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'mileage_initial').'</strong></div>
		<div class="ui-block-b">'.$model->mileage_initial.' '.$model->getMileageType().'</div>';
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'mileage').'</strong></div>
		<div class="ui-block-b">'.$model->mileage.' '.$model->getMileageType().' '.CHtml::link(Yii::t('app','update'), array('userCar/update', 'id'=>$model->id), array('data-mini'=>'true','data-theme'=>'e', 'data-role'=>'button', 'data-inline'=>'true', 'data-icon'=>'gear', 'data-iconpos'=>'notext')).'</div>';
echo '<div class="ui-block-a"><strong>'.CHtml::activeLabel($model,'year_built').'</strong></div>
		<div class="ui-block-b">'.$model->year_built.'</div>';
?>
</div>
<?php
echo '
<ul data-role="listview" data-inset="true" class="crons" data-theme="c">
	<li data-role="list-divider"><h5>'.Yii::t('app','Expenses').':</h5></li>';
if(!empty($expenses)){
	foreach($expenses as $expense){
		echo '<li>
				<div class="ui-grid-a">
					<div class="ui-block-a">'.$expense->getCategory().'</div>
					<div class="ui-block-b">'.$expense->getValue().'</div>
				</div>
				<ul>';
		echo '<li>'.$expense->time.'</li>';
		if($expense->title)
			echo '<li>'.$expense->title.'</li>';
		if($expense->description)
			echo '<li>'.$expense->description.'</li>';
			echo '<li>'.$expense->getValue().'</li>';
		echo			'<li>'.CHtml::link(Yii::t('app', 'Update'), array('expense/update','id'=>$expense->id)).'</li>';
		echo			'<li data-icon="delete" data-theme="e">'.CHtml::linkButton(Yii::t('app', 'Delete'), array('submit'=>array('expense/delete','id'=>$expense->id, 'returnUrl'=>Yii::app()->createUrl('userCar/view', array('id'=>$model->id))),'confirm'=>'Are you sure you want to delete this item?', 'data-ajax'=>'false')).'</li>
					<li data-icon="arrow-l">
						'.CHtml::link(Yii::t('app', 'Back'), '', array('data-rel'=>'back')).'
					</li>
				</ul>
			</li>';
	}
}
		echo '<li><div data-role="controlgroup" data-type="horizontal" class="small center">';
		echo CHtml::link(Yii::t('app','Expense'), array('expense/create', 'car_id'=>$model->id), array('data-role'=>'button', 'data-icon'=>'plus'));
		echo CHtml::link(Yii::t('app','Show all'), array('expense/index', 'car_id'=>$model->id), array('data-role'=>'button', 'data-ajax'=>'false'));
		echo '</div></li>';
echo '</ul>';

echo '
<ul data-role="listview" data-inset="true" class="crons" data-theme="c">
	<li data-role="list-divider"><h5>'.Yii::t('app','Reminders').':</h5></li>';
if(!empty($crons)){
	foreach($crons as $cron){
		echo '<li>
				<div class="ui-grid-a">
					<div class="ui-block-a">'.CronType::model()->findByPk($cron->type)->getName().'</div>
					<div class="ui-block-b">'.($model->getMileageGap($cron->mileage + $cron->user_mileage)).' '.$model->getMileageType().'</div>
				</div>
				<ul data-inset="true">
					<li data-icon="search">'.CHtml::link(Yii::t('app', 'Shop and service stations'), array('cron/findStore', 'id'=>$cron->id, 'car_id'=>$model->id), array('data-ajax'=>"false")).'</li>
					<li data-icon="gear">'.CHtml::link(Yii::t('app', 'Update'), array('cron/update', 'id'=>$cron->id, 'car_id'=>$model->id)).'</li>
					<li data-icon="delete" data-theme="e">'.CHtml::linkButton(Yii::t('app', 'Delete'), array('submit'=>array('cron/delete','id'=>$cron->id, 'returnUrl'=>Yii::app()->createUrl('userCar/view', array('id'=>$model->id))),'confirm'=>'Are you sure you want to delete this item?', 'data-ajax'=>'false')).'</li>
					<li data-icon="arrow-l">
						'.CHtml::link(Yii::t('app', 'Back'), '', array('data-rel'=>'back')).'
					</li>
				</ul>
			</li>';
	}
}
	echo '<li><div data-role="controlgroup" data-type="horizontal" class="center">';
	echo CHtml::link(Yii::t('app','Add reminder'), array('cron/create', 'car_id'=>$model->id), array('data-role'=>'button', 'data-icon'=>'plus'));
	echo '</div></li>';
echo '</ul>';

if(!empty($history))
	echo CHtml::link(Yii::t('app','View Service History'), array('cronHistory/index', 'car_id'=>$model->id), array('data-role'=>'button','data-ajax'=>'false'));

?>
