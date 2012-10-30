<?php if (!$one_car && ($this->last_car !== $data->car_id)){ 
	$this->last_car = $data->car_id;
	$newcar = 1;
?>
	<li data-role="list-divider">
		<?php echo CHtml::link(UserCar::model()->findByPk($data->car_id)->name, array('userCar/view', 'id'=>$data->car_id), array('data-ajax'=>'false')); ?>
	</li>
<?php } ?>
<?php if($index==0 || $newcar){ ?>
	</li>
	<li data-role="list-divider">
		<div class="ui-grid-a">
			<div class="ui-block-a">
			<?php echo CHtml::encode($data->getAttributeLabel('type')); ?>
			</div>
			<div class="ui-block-d">
			<?php echo CHtml::encode($data->getAttributeLabel('last_update')); ?>
			</div>
		</div>
	</li>
<?php } ?>

<li>
	<div class="ui-grid-a">
		<div class="ui-block-a">
		<?php echo CHtml::encode($data->getType()); ?>
		</div>
		<div class="ui-block-b">
		<?php echo CHtml::encode($data->last_update); ?>
		</div>
	</div>
	<ul>
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
					<?php echo CHtml::encode($data->getAttributeLabel('type')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->getType()); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('mileage')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->mileage); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('value')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->value); ?>
				</div>
			</div>
		</li>
		<li>
			<div class="ui-grid-a">
				<div class="ui-block-a">
					<?php echo CHtml::encode($data->getAttributeLabel('last_update')); ?>
				</div>
				<div class="ui-block-b">
					<?php echo CHtml::encode($data->last_update); ?>
				</div>
			</div>
		</li>
		<?php if(!isset($data->user_period)){ ?>
		<li data-icon="delete" data-theme="f">
			<?php echo CHtml::linkButton(Yii::t('app', 'Delete'), array('data-ajax'=>'false', 'submit'=>array('cronHistory/delete','id'=>$data->id),'confirm'=>$data->id.'Are you sure you want to delete this item?')); ?>
		</li>
		<?php } ?>
		<li data-icon="arrow-l">
			<?php echo CHtml::link(Yii::t('app', 'Back'), '', array('data-rel'=>'back')); ?>
		</li>
	</ul>
</li>