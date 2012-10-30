<?php
$this->breadcrumbs=array(
	'Shops'=>array('index'),
	$model->name,
);

$this->menu=array(
//	array('label'=>'List Shop', 'url'=>array('index')),
//	array('label'=>'Create Shop', 'url'=>array('create'), 'visible'=>User::model()->findByPk(Yii::app()->user->id)->isSeller()),
	array('label'=>Yii::t('app','Edit Shop'), 'url'=>array('update', 'id'=>$model->id), 'visible'=>Yii::app()->user->id==$model->uid, 'linkOptions'=>array('data-ajax'=>'false')),
	array('label'=>Yii::t('app','Delete Shop'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>Yii::app()->user->id==$model->uid),
	array('label'=>Yii::t('app','Manage Shop'), 'url'=>array('admin'), 'visible'=>Yii::app()->user->name=="admin"),
);
?>

<h4><?php echo Yii::t('app','View shop: ').$model->name; ?></h4>
<?php if($model->description){
	echo '
	<div class="info-block">
		<h5>'.$model->getAttributeLabel('description').'</h5>
		<p>'.$model->description.'</p>
	</div>';
} ?>
<?php if($model->phones){
	echo '
	<div class="info-block">
		<h5>'.$model->getAttributeLabel('phones').'</h5>
		<p>'.$model->phones.'</p>
	</div>';
} 
 
/*
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
//		'name',
//		'address',
		'description',
		'phones',
	),
)); 
*/
if($brands->totalItemCount){
	echo '<h5>'.Yii::t('app', 'Suported car brands').'</h5>';
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$brands,
		'itemView'=>'_defview_brands',
		'enableSorting'=>false,
		'enablePagination'=>false,
		'separator'=>'',
		'summaryText'=>'',
		'emptyText'=>'',
		'template'=>'{items}',
		'itemsTagName'=>'ul data-role="listview" data-inset="true"',
		'htmlOptions'=>array(
			'data-theme'=>'c',
		),
		'viewData'=>array(
			'model'=>$model,
		),
	));
}

if(Yii::app()->user->id==$model->uid){
	echo CHtml::link(Yii::t('app','Add supported brands'),array('shop/addDefinition', 'id'=>$model->id, 'item'=>'brand'), array('data-role'=>'button'));
}

if($cron_types->totalItemCount){
	echo '<h5>'.Yii::t('app', 'Suported part types').'</h5>';
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$cron_types,
		'itemView'=>'_defview_crontypes',
		'enableSorting'=>false,
		'enablePagination'=>false,
		'separator'=>'',
		'summaryText'=>'',
		'emptyText'=>'',
		'template'=>'{items}',
		'itemsTagName'=>'ul data-role="listview" data-inset="true"',
		'htmlOptions'=>array(
			'data-theme'=>'c',
		),
		'viewData'=>array(
			'model'=>$model,
		),
	));
}

if(Yii::app()->user->id==$model->uid){
	echo CHtml::link(Yii::t('app','Add services'),array('shop/addDefinition', 'id'=>$model->id, 'item'=>'cron_type'), array('data-role'=>'button'));
}
 ?>
