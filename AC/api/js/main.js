if (window.location.hash == '#_=_')
	window.location.hash = '';

function hideAddressBar(){
  if(document.documentElement.scrollHeight<window.outerHeight/window.devicePixelRatio)
    document.documentElement.style.height=(window.outerHeight/window.devicePixelRatio)+'px';
  setTimeout(function(){window.scrollTo(1,1);},0);
}
window.addEventListener("load",function(){hideAddressBar();});
window.addEventListener("orientationchange",hideAddressBar());

$(document).ready(function(){
//	$.mobile.fixedToolbars.setTouchToggleEnabled(false);
	   	   
	//Registration selects
	$('#register-form select.select-car').live('change', function(){
		var self = $(this).parent();
		var val = $(this).val();
		self.append('<div class="preloader"></div>');
		$.ajax({
			url: $(this).attr('actionUrl') + '&table=' + $(this).attr('name') + '&value=' + val,
			context: document.body,
			success: function(data){
				self.find('.preloader').remove();
				if(self.next().hasClass('ui-select'))
					self.next().remove();
				self.after(data);
				if(( self.next().attr('name')=='car_variant' || self.next().attr('name')=='car_version' ) && val){
					self.parents('form').find('input[type="submit"]').button('enable');
					$('.car_image').remove();
					self.parents('form').append('<img class="car_image" src="images/cars/'+$('#car_image').attr('value')+'" />');
				}else{
					$('.car_image').remove();
					self.parents('form').find('input[type="submit"]').button('disable');
					self.next().selectmenu();
					if(self.next().find('.ui-select').attr('name')=='car_make'){
						self.remove();
					}
				}
			}
		});
	});
	$('#User_agreeterms, #User_role').live('change', function(){
		if($(this).attr("checked")){
			$('input[type="submit"]').button('enable');
			$(this).attr('value',1);
			$(this).attr('checked',true);
		}else{
			$('input[type="submit"]').button('disable');
			$(this).attr('value',0);
			$(this).attr('checked',false);
		}
	});
	
	$('.scroller').scroller();
	$('.scroller').live('click', function(){$(this).scroller();}); //For ajax-generated fields
	$('.scroller.year').scroller({ preset: 'date', dateFormat: 'yy' });
	$('.scroller.year').live('click', function(){$(this).scroller({ preset: 'date', dateFormat: 'yy' });}); //For ajax-generated fields
	$('.scroller.time').scroller({ preset: 'time' });
	$('.scroller.time').live('click', function(){$(this).scroller({ preset: 'time' });}); //For ajax-generated fields

	$('.cron-types').live('change', function(){
		var val = $(this).val();
//		alert($(this).attr('actionUrl') + '?id=' + val);
		$.ajax({
			url: $(this).attr('actionUrl') + '&id=' + val,
			context: document.body,
			success: function(data){
				var vals = data.split('|');
				$('input[name="Cron[user_mileage]"]').attr('value',vals[0]);
				$('input[name="Cron[user_period]"]').attr('value',vals[1]);
			}
		});
	});
	$('.scroll-to').click(function(){
		$.mobile.silentScroll($(this).position().top);
	});
	
});
//------ Google Plus button code
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
//------
