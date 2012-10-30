<?php $this->pageTitle=Yii::app()->name; ?>

<?php 
if(Yii::app()->user->isGuest){
	echo '<div class="introduction">';
	echo Yii::t('app', '<h4>Welcome to {site_name}</h4>', array(
		'{site_name}'=>Yii::app()->name,
	));
	echo Content::getStatic('home-introduction');
	echo '</div>';
	echo CHtml::link(Yii::t('app','Login'), array('/site/login'), array('data-role'=>'button'));
	echo '<h5>'.Yii::t('app', 'Don\'t have account?').'</h5>';
	echo CHtml::link(Yii::t('app','User Registration'), array('/site/register'), array('data-role'=>'button'));
	echo CHtml::link(Yii::t('app','Seller Registration'), array('/site/register', 'seller'=>1), array('data-role'=>'button'));
}else{
	echo '	<div class="welcome">
				<h5>'.Yii::t('app','Hi, {username}', array('{username}'=>Yii::app()->user->name)).'</h5>';
	echo '		<p>'.Yii::t('app','Your last login was: {datetime}', array('{datetime}'=>$user->last_login)).'</p>
			</div>';
	if(!$user->location){
		$this->renderInternal('protected/views/user/_locationTerms.php');
	}

	echo '<div class="dashboard-menu">';
	if($user->isSeller()){
		echo CHtml::link(Yii::t('app', 'Your shops'), array('shop/index'), array('data-role'=>'button','data-icon'=>'shop','data-iconpos'=>'top','class'=>'dashboard-item'));
	}
	echo CHtml::link(Yii::t('app', 'Your cars'), array('userCar/index'), array('data-role'=>'button','data-icon'=>'car','data-iconpos'=>'top','class'=>'dashboard-item'));
	echo CHtml::link(Yii::t('app', 'Expenses'), array('expense/index'), array('data-ajax'=>'false', 'data-role'=>'button','data-icon'=>'expense','data-iconpos'=>'top','class'=>'dashboard-item'));
	echo '</div>';
	if($user->isAdmin()){
		echo CHtml::link(Yii::t('app', 'Admin panel'), array('site/admin'), array('data-role'=>'button', 'data-ajax'=>'false'));
	}
	if(!$user->isPremium()){
		echo CHtml::link(Yii::t('app', 'Get Premium'), array('site/getPremium'), array('data-role'=>'button', 'data-ajax'=>'false'));
	}
}
Yii::app()->clientScript->registerScriptFile('https://apis.google.com/js/plusone.js');
Yii::app()->clientScript->registerScriptFile('http://userapi.com/js/api/openapi.js?49');
?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "http://connect.facebook.net/ru_RU/all.js#xfbml=1&appId=207855105987264";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript">
  VK.init({apiId: 2912194, onlyWidgets: true});
</script>

<center>

<p>
	<div class="g-plusone" data-href="http://autocron.ru"></div>

	<a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-count="none">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</p>

<div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>

<div id="vk_like"></div>
<script type="text/javascript">
VK.Widgets.Like("vk_like", {type: "mini", height: 18});
</script>

</center>
