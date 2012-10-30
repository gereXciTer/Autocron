<?php if (!$one_car && ($this->last_car !== $data->car_id)){ 
	$this->last_car = $data->car_id;
	$newcar = 1;
?>
	<li data-role="list-divider">
		<?php echo CHtml::encode(UserCar::model()->findByPk($data->car_id)->name); ?>
	</li>
<?php } ?>
<?php if($index==0 || $newcar){ ?>
	</li>
	<li data-role="list-divider">
		<div class="ui-grid-a">
			<div class="ui-block-a">
			<?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>
			</div>
			<div class="ui-block-d">
			<?php echo CHtml::encode($data->getAttributeLabel('value')); ?>
			</div>
		</div>
	</li>
<?php } ?>

<li>
	<div class="ui-grid-a">
		<div class="ui-block-a">
		<?php echo CHtml::encode($data->getCategory()); ?>
		</div>
		<div class="ui-block-b">
		<?php echo CHtml::encode($data->getValue()); ?>
		</div>
	</div>
	<ul data-inset="true">
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('car_id')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode(UserCar::model()->findByPk($data->car_id)->name); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->getCategory()); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('time')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->time); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('title')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->title); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('description')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->description); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('value')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->getValue()); ?>
				</div>
			</div>
		</li>
		<li>
			<?php echo CHtml::link(Yii::t('app', 'Update'), array('expense/update','id'=>$data->id)); ?>
		</li>
		<li data-icon="delete" data-theme="f">
			<?php echo CHtml::linkButton(Yii::t('app', 'Delete'), array('submit'=>array('expense/delete','id'=>$data->id),'confirm'=>'Are you sure you want to delete this item?')); ?>
		</li>
		<li data-icon="arrow-l">
			<?php echo CHtml::link(Yii::t('app', 'Back'), '', array('data-rel'=>'back')); ?>
		</li>
	</ul>
</li>