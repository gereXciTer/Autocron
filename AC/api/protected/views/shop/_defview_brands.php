<li data-theme="d"><?php
if(!empty($data)){ 
	if($model->uid !== Yii::app()->user->getId()){
		echo $data->brand_id ? CarMake::model()->findByPk($data->brand_id)->name : Yii::t('app', 'All brands');
	}else{
		echo CHtml::link($data->brand_id ? CarMake::model()->findByPk($data->brand_id)->name : Yii::t('app', 'All brands'), '', array('data-iconpos'=>'none'));
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