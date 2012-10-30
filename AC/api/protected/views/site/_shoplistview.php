<li><?php
if(!empty($data)){ 
	echo CHtml::link($data->name, array('shop/view', 'id'=>$data->id), array('data-ajax'=>'false'));
	echo CHtml::link(Yii::t('app','Update'), 
					array('shop/update', 'id'=>$data->id), 
					array(
						'data-ajax'=>'false',
						'data-role'=>"button",
						'data-icon'=>"gear",
						'data-iconpos'=>"notext",
					));
}
?></li>