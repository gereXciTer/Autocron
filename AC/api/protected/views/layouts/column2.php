<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div id="content">
		<?php echo $content; ?>
	</div>

	<div id="mainmenu" data-role="navbar" >
<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>'',
	));
	$this->widget('zii.widgets.CMenu', array(
		'items'=>$this->menu,
		'htmlOptions'=>array('class'=>'operations'),
	));
	$this->endWidget();

?>
	</div>
</div>
<?php $this->endContent(); ?>