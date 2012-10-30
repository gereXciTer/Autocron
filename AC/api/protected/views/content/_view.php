<div class="view">
	
	<?php echo ($index+1).'. '; ?>
	<?php echo CHtml::link(CHtml::encode($data->code), array('view', 'id'=>$data->id)); ?>

</div>