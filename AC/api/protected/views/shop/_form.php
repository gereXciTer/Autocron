<?php
	Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps/api/js?sensor=true&language=en');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.map.full.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.map.extensions.js');
?>
<div class="form" data-role="fieldcontain">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shop-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'data-ajax'=>'false',
	),
)); ?>


	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>

		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
		
		<?php echo $form->hiddenField($model,'coordinates'); ?>

		<?php echo $form->hiddenField($model,'country'); ?>

		<?php echo $form->hiddenField($model,'state'); ?>

		<?php echo $form->hiddenField($model,'address'); ?>

	<div data-role="collapsible" data-mini="true" class="scroll-to">
		<h5><?php echo Yii::t('app','Change location'); ?></h5>
		<a id="go-to-current-location" data-role="button" data-icon="search" data-iconpos="left"><?php echo Yii::t('app','Go to current location');?></a>
		<div id="map_canvas"></div>
	</div>		

<?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'coordinates'); ?>
		<?php echo $form->textField($model,'coordinates',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'coordinates'); ?>
	</div>
*/?>

	<div class="row">
		<?php echo $form->labelEx($model,'phones'); ?>
		<?php echo $form->textField($model,'phones',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'phones'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div id="map_canvas"></div>
<script>
$(document).ready(function(){

	$('.scroll-to').click(function(){
		$.mobile.silentScroll($(this).position().top);
		$('#map_canvas').gmap('refresh');
	});
	
	var userLocation = '<?php echo str_replace('(','', str_replace(')','',$model->coordinates) ); ?>';
	function findCurrentLocation(location) {
        $('#map_canvas').gmap('search', {'location': location}, function(results, status) {
			if ( status === 'OK' ) {
				userLocation = results[0].formatted_address;
				if(!$('#Shop_coordinates').attr('value')){
					$('#Shop_coordinates').attr('value', location);
				}
			}
        });
	}
	function setLocation(location) {
        $('#map_canvas').gmap('search', {'location': location}, function(results, status) {
			if ( status === 'OK' ) {
				$.each(results[0].address_components, function(i,v) {
					if ( v.types[0] == "administrative_area_level_1" || 
						 v.types[0] == "administrative_area_level_2" ) {
						$('#Shop_state').attr('value',v.long_name);
					} else if ( v.types[0] == "country") {
						$('#Shop_country').attr('value',v.long_name);
					}
				});
				$('#Shop_address').attr('value',results[0].formatted_address);
				$('#Shop_coordinates').attr('value', location);
			}
        });
	}
	function changeLocation(clientPosition){
		$('#map_canvas').gmap('addMarker', {
			'id':'locationMarker',
			'position': clientPosition,
			'draggable': true, 
			'bounds': true
		}).dragend(function(event) {
			setLocation(event.latLng);
		});
	}

	$('#go-to-current-location').click(function(){
		if(!$(this).attr('value')){
			$('#map_canvas').gmap('clear', 'markers');
			$('#map_canvas').gmap('getCurrentPosition', function(position, status) {
				var clientPosition;
				clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				changeLocation(clientPosition);
				setLocation(clientPosition);
			});
		}
	});

	setTimeout(function(){
		$('#map_canvas').gmap().bind('init', function(event, map) {
			var clientPosition;
			$('#map_canvas').gmap('getCurrentPosition', function(position, status) {
				if(userLocation.length){
					changeLocation(userLocation);
				}else{
					clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					changeLocation(clientPosition);
					$('#map_canvas').gmap('addShape', 'Circle', { 
						'strokeWeight': 0, 
						'fillColor': "#008595", 
						'fillOpacity': 0.25, 
						'center': clientPosition, 
						'radius': 15, 
						'clickable': false 
					});
	//				findCurrentLocation(clientPosition);
				}
			});
			$(map).click( function(event){
				$('#map_canvas').gmap('clear', 'markers');
				changeLocation(event.latLng);
				setLocation(event.latLng);
			});
			$('#map_canvas').gmap('option', 'maxZoom', 18);
			$('#map_canvas').gmap('option', 'disableDefaultUI', true);
			$('#map_canvas').gmap('option', 'zoomControl', true);
		});
	}, 1000);
	
});

</script>