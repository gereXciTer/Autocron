<?php
		echo '<div class="reminders">'.Yii::t('app','You didn\'t set your location.').'</div>
			<div data-role="collapsible" data-content-theme="d" class="location-terms">
			   <h3>'.Yii::t('app','Learn more about location sharing').'</h3>
			   <p>'.Yii::t('app','We don\'t use your private information like location and don\'t share it with third-party persons.<br />By sharing your location you became able to:<ul><li>find stores near you where you can buy parts for your car</li><li>share your experience with others</li><li>get help from users near you</li><li>and moch more...</li></ul>').
					CHtml::link(Yii::t('app', 'Set location in your profile'), array('user/profile'), array('data-ajax'=>'false', 'data-role'=>'button')).'</p>
			</div>';
?>