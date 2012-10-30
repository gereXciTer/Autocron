<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,height=device-height,width=device-width,user-scalable = no">
	<meta name="apple-mobile-web-app-capable" content="yes" />	

	<link rel="apple-touch-startup-image" href="<?php echo Yii::app()->request->baseUrl; ?>/images/splash.png" />
	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/app-icon.png" />
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/app-icon.png"/>
	<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::app()->request->baseUrl; ?>/images/app-icon.png"/>

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.mobile-1.1.0.min.css" />
<?php /*
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/autocron.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.mobile.structure-1.1.0-rc.1.min.css" />
*/ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/mobiscroll-1.5.3.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/app.css" />
<?php
	if(isset($this->customStyle) && $this->customStyle)
		echo '<link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/css/custom_'.$this->customStyle.'.css" />';
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mobile-1.1.0.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/mobiscroll-1.5.3.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/main.js');
//	Yii::app()->clientScript->registerScriptFile('https://www.google.com/jsapi');
?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31043103-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
	<div data-role="page" data-theme="b" data-fullscreen="false">
	
		<div data-role="header" data-position="fixed">
			<h1><?php 
//				echo Yii::t('app', Yii::app()->name);
				//echo CHtml::link(Yii::t('app', $this->pageTitle ? $this->pageTitle : Yii::app()->name), array('site/index'));
				echo CHtml::link(
						'<img src="images/logo.png" alt="'.Yii::t('app', Yii::app()->name).'" />',
						array('/site/page', 'view'=>'about')
					);
				echo ' '.($this->pageTitle ? $this->pageTitle : Yii::t('app', Yii::app()->name));
				?>
			</h1>
			<?php
				if(Yii::app()->controller->id=='site' && Yii::app()->controller->action->id=='index')
					$leftButton = array('label'=>Yii::t('app','About'), 'url'=>array('/site/page', 'view'=>'about'), 'htmlOptions'=>array('data-icon'=>"star", 'class'=>'ui-btn-left'));
				else
					$leftButton = array('label'=>Yii::t('app','Back'), 'htmlOptions'=>array('data-rel'=>"back", 'data-icon'=>"arrow-l", 'class'=>'ui-btn-left', 'data-ajax'=>"false"));

				$menu = array(
					$leftButton,
//					array('label'=>'Contact', 'url'=>array('/site/contact'), 'htmlOptions'=>array('class'=>'ui-btn-right')),
					array('label'=>Yii::t('app','Login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest, 'htmlOptions'=>array('data-ajax'=>"false", 'data-icon'=>"arrow-d", 'class'=>'ui-btn-right')),
					array('label'=>Yii::t('app','Logout'), 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest, 'htmlOptions'=>array('data-ajax'=>"false", 'data-icon'=>"delete", 'class'=>'ui-btn-right'))
				);
				foreach($menu as $mitem){
					if(!isset($mitem['visible']) || $mitem['visible'])
						echo CHtml::link($mitem['label'], $mitem['url'], $mitem['htmlOptions']);
				}
				unset($mitem);
			?>
		</div><!-- /header -->
	
<?php /*
		<div id="mainmenu" data-role="navbar">
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'tagName'=>'ul',
			'links'=>$this->breadcrumbs,
			'homeLink'=>'',
			'separator'=>'</li><li>',
			'htmlOptions'=>array('data-role'=>'header', 'data-position'=>'inline'),
		)); ?>
		</div><!-- breadcrumbs -->
*/ ?>
		
		<div data-role="content">
<?php
//echo CLocale::getInstance(Yii::app()->request->getPreferredLanguage())->getCurrencySymbol('RUR');
$flashes =Yii::app()->user->getFlashes();
	foreach($flashes as $key => $message) {
		echo '<div class="dialog flash-' . $key . '">
				<div class="dialog-content">
					<h3>'.$key.'</h3>
					<div class="text">' . $message . '</div>
				</div>
			</div>';
	}
if($flashes){
?>
<div id="overlay"></div>
<script>
$(document).ready(function(){
	var timeout = 5000;
	var i = 1;
	$('#overlay').show();
	$('#overlay, .dialog').click(function(){
		$('.dialog').each(function(){
			$(this).fadeOut();
		});
		$(this).fadeOut();
	});
	$('.dialog').each(function(){
		var self = $(this);
		$(this).show();
		setTimeout(function(){
			self.fadeOut();
		}, i*timeout);
		i++;
	});
	setTimeout(function(){
		$('#overlay').fadeOut();
	}, (i-1)*timeout);
});
</script>
<?php } ?>
			<?php echo $content; ?>
		</div><!-- /content -->
	
		<div id="footer" data-role="footer" data-position="fixed">
			<div id="mainmenu" data-role="navbar" class="ui-state-persist" data-position="inline" >
				<?php 
				$mainmenu = array();
				$mainmenu[] = array('label'=>Yii::t('app','Home'), 'url'=>array('/site/index'),'linkOptions'=>array('data-ajax'=>"false"));
				if(!Yii::app()->user->isGuest && User::model()->findByPk(Yii::app()->user->getId())->isSeller())
					$mainmenu[] = array('label'=>Yii::t('app','Shops'), 'url'=>array('/shop/index'),'linkOptions'=>array('data-ajax'=>"false"));
				$mainmenu[] = array('label'=>Yii::t('app','Profile'), 'url'=>array('/user/profile'),'linkOptions'=>array('data-ajax'=>"false"));
				$this->widget('zii.widgets.CMenu',array(
					'itemTemplate'=>'{menu}',
					'items'=>$mainmenu,
					'htmlOptions'=>array(
						
					),
				)); ?>
			</div><!-- mainmenu -->
		</div><!-- footer -->

	</div><!-- /page -->
<?php
 /*
	foreach(Yii::app()->user->getFlashes() as $key => $message) {
		echo '<div data-role="page" class="flash-' . $key . '">
				<div data-role="header" data-theme="d"><h1>'.$key.'</h1></div>
				<div data-role="content" data-theme="c">' . $message . '</div>
			</div>\n';
			?>
	<script>
		$(document).ready(function(){
			$.mobile.changePage( $('.flash-<?php echo $key; ?>').trigger( "create" ), {transition: 'pop'} );
//			$.mobile.changePage();
		});
	</script>
			<?php
	}
*/ ?>

	<div id="terms" data-role="page">
		<div data-role="header">
			<h1><?php echo Yii::t('app','Terms of use'); ?></h1>
		</div><!-- /header -->
		<div data-role="content">
			<?php echo Yii::t('app','Terms of use'); ?>
		</div>
	</div>

</body>
</html>