<li><?php
if(!empty($data)){ 
	echo CHtml::link($data->name.'<div class="reminders">'.implode('<br />',$data->getReminders()).'</div>', array('/userCar/view', 'id'=>$data->id), array('data-ajax'=>'false'));
	echo CHtml::link(Yii::t('app','Update'), 
					array('/userCar/update', 'id'=>$data->id), 
					array(
//						'data-ajax'=>'false',
						'data-role'=>"button",
						'data-icon'=>"gear",
						'data-iconpos'=>"notext",
					));
}
?></li>