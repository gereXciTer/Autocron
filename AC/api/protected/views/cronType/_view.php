<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('period')); ?>:</b>
	<?php echo CHtml::encode($data->period); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mileage')); ?>:</b>
	<?php echo CHtml::encode($data->mileage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('primary')); ?>:</b>
	<?php echo CHtml::encode($data->primary); ?>
	<br />


</div>