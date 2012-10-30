<?php
	Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps/api/js?sensor=true&language=en');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.map.full.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.map.extensions.js');
?>

<div class="form" data-role="fieldcontain">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>

		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'password'); ?>

		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'password_repeat'); ?>

		<?php echo $form->labelEx($model,'locale'); ?>
		<?php
			$languages = array();
			foreach(Yii::app()->params['languages'] as $l){
				$languages[$l] = CLocale::getInstance(Yii::app()->language)->getLanguage($l);
			}
			  echo $form->dropDownList($model,'locale',$languages); ?>
		<?php echo $form->error($model,'locale'); ?>

		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textField($model,'location',array( 'data-inline'=>"true")); ?>
		<?php echo $form->error($model,'location'); ?>

		<?php echo $form->hiddenField($model,'coordinates'); ?>

	<div data-role="collapsible" data-mini="true" class="scroll-to">
		<h5><?php echo Yii::t('app','Change location'); ?></h5>
		<a id="go-to-current-location" data-role="button" data-icon="search" data-iconpos="left"><?php echo Yii::t('app','Go to current location');?></a>
		<div id="map_canvas"></div>
	</div>		


	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app', 'Update'), array('data-ajax'=>"false")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(document).ready(function(){

	$('.scroll-to').click(function(){
		$.mobile.silentScroll($('#User_location').position().top);
		$('#map_canvas').gmap('refresh');
	});
	
	var userLocation = '';
	function findCurrentLocation(location) {
        $('#map_canvas').gmap('search', {'location': location}, function(results, status) {
			if ( status === 'OK' ) {
				userLocation = results[0].formatted_address;
				if(!$('#User_location').attr('value')){
					$('#User_location').attr('value', userLocation);
					$('#User_coordinates').attr('value', location);
				}
			}
        });
	}
	function setLocation(location) {
        $('#map_canvas').gmap('search', {'location': location}, function(results, status) {
			if ( status === 'OK' ) {
				userLocation = results[0].formatted_address;
				$('#User_location').attr('value', userLocation);
				$('#User_coordinates').attr('value', location);
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
	setTimeout(function(){
	$('#map_canvas').gmap().bind('init', function(event, map) { 
		$('#map_canvas').gmap('getCurrentPosition', function(position, status) {
			if ( status === 'OK' ) {
				if(!$('#User_coordinates').attr('value') && $('#User_location').attr('value')){
					$('#map_canvas').gmap('search', { 'address': $('#User_location').attr('value') }, function(results, status) {
						if ( status === 'OK' ) {
							changeLocation(results[0].geometry.location);
						}else{
							var clientPosition;
							clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
							changeLocation(clientPosition);
						}
					});
				}else if($('#User_coordinates').attr('value')){
					changeLocation($('#User_coordinates').attr('value').replace('(','').replace(')',''));
				}else{
					var clientPosition;
					clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					changeLocation(clientPosition);
				}
				
				$('#map_canvas').gmap('addShape', 'Circle', { 
					'strokeWeight': 0, 
					'fillColor': "#008595", 
					'fillOpacity': 0.25, 
					'center': clientPosition, 
					'radius': 15, 
					'clickable': false 
				});
				findCurrentLocation(clientPosition);
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
	$('#User_location').change(function(){
		$('#map_canvas').gmap('search', { 'address': $(this).attr('value') }, function(results, status) {
			if ( status === 'OK' ) {
				$('#map_canvas').gmap('clear', 'markers');
				changeLocation(results[0].geometry.location);
				setLocation(results[0].geometry.location);
			}
		});
	});
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
});

</script>