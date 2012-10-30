<li data-theme="d"><?php
if(!empty($data)){ 
	if($model->uid !== Yii::app()->user->getId()){
		echo CronType::model()->findByPk($data->cron_type_id)->name;
	}else{
		echo CHtml::link(CronType::model()->findByPk($data->cron_type_id)->name, '');
		echo CHtml::linkButton(Yii::t('app','Delete'), 
						array(
							'submit'=>array('shop/deleteDefinition','id'=>$data->id,'shop_id'=>$data->shop_id),
							'confirm'=>'Are you sure you want to delete this item?',
							'data-ajax'=>'false',
							'data-role'=>"button",
							'data-icon'=>"delete",
							'data-iconpos'=>"notext",
						));
	}
}
?></li>