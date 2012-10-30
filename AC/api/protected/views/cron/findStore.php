<?php if(!$user->location){ 
	$this->renderInternal('protected/views/user/_locationTerms.php');
}else{
	Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps/api/js?sensor=true&language=en');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.map.full.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ui.map.extensions.js');

	echo '<h4 id="shops-list-header">'.Yii::t('app', 'Shops near you').':</h4>';
	echo '<ul id="shops-list" data-inset="true"><div class="preloader"></div>';
	echo '</ul>';
 ?>
 <div id="map_canvas" class="hidden"></div>
	<script>
	$(document).ready(function(){
		$('.search-pages a').live('click', function(e){
			e.preventDefault();
			$('.search-pages a').removeClass('active');
			var val = $(this).attr('href');
			var self = $(this);
	//		alert($(this).attr('actionUrl') + '?id=' + val);
			$.ajax({
				url: val + '&searchstring=' + $('input.search').attr('value'),
				context: document.body,
				success: function(data){
					$('.search-results').html(data);
					$('.search-results').listview('refresh');
					self.addClass('active');
				}
			});
		});

		setTimeout(function(){
			$('#map_canvas').gmap().bind('init', function(event, map) {
				$('#map_canvas').gmap('addMarker', {
					'id':'locationMarker',
					'position': '<?php echo str_replace('(','', str_replace(')','',$user->coordinates) ); ?>',
					'draggable': true, 
					'bounds': true
				}, function(map, marker) {
					$('#map_canvas').gmap('search', {'location': marker.getPosition()}, function(results, status) {
						var country;
						var state;
						if ( status === 'OK' ) {
							$.each(results[0].address_components, function(i,v) {
								if ( v.types[0] == "administrative_area_level_1" || 
									 v.types[0] == "administrative_area_level_2" ) {
									state = v.long_name;
								} else if ( v.types[0] == "country") {
									country = v.long_name;
								}
							});
							$.ajax({
								url: '<?php echo Yii::app()->createUrl('cron/findShops', array(
												'car_id'=>$_GET['car_id'],
											));?>' + '&id=<?php echo $model->id; ?>' + '&country=' + country + '&state=' + state,
								context: document.body,
								success: function(data){
									$('#shops-list').html(data);
									if(data.length){
										$('#shops-list-header').show();
										$('#shops-list').listview();
									}
								}
							});
						}
					});
				});
			});
		}, 0);

	});
	</script>
	<h4><?php echo Yii::t('app', 'Google search results'); ?>:</h4>
	<input type="search" class="search" value="<?php echo $searchstring; ?>" />
	<ul data-role="listview" data-inset="true" class="search-results" data-theme="c">
	<?php
	if(isset($searchResultsArray)){
		foreach($searchResultsArray["results"] as $result){
			echo '<li><a href="'.$result['unescapedUrl'].'"><h5>'.$result['title'].' - '.$result['visibleUrl'].'</h5>';
			echo '<p>'.$result['content'].'</p>';
			echo '</a></li>';
		}
	}else{
		echo Yii::t('app', 'No results found');
	}
	?>
	</ul>
	<div class="search-pages" data-role="controlgroup" data-type="horizontal">
	<?php
	$pp = 4;
	$i = 0;
	if(isset($searchResultsArray)){
		foreach($searchResultsArray["cursor"]['pages'] as $page){
			echo CHtml::link($page['label'], array('cron/updateSearchResults', 'start'=>$page['start']), array('index'=>$i++, 'data-ajax'=>'false', 'data-role'=>'button', 'data-theme'=>"c")).' ';
		}
	}
	?>
	</div>
<script>
$(document).ready(function(){
	function rebuildPages(o, s){
		var pp = 2;
		s = parseInt(s);
		
		o.each(function(index){
			if( index >= (s - pp) && index <= (s + pp) ){
				$(this).show();
			}else
			if(index!=0 && index != o.length-1){
				$(this).hide();
			}
		});
	}
	$('div.search-pages a').live('click', function(){
		rebuildPages($('div.search-pages a'), $(this).attr('index'));
	});
});
</script>
	<?php
}
/*
	Yii::app()->clientScript->registerScriptFile('https://www.google.com/jsapi');
	
?>
<script language="Javascript" type="text/javascript">

    //<!
    google.load('search', '1.0', {
//		'nocss':true,
		"nooldnames" : true
	});

    function OnLoad() {
	  // Create a search control
      var searchControl = new google.search.SearchControl();

      // Add in a full set of searchers
      var localSearch = new google.search.LocalSearch();
 //     searchControl.addSearcher(localSearch);
	  var options = new google.search.SearcherOptions();
	  var websearch = new google.search.WebSearch();
	  options.setExpandMode(google.search.SearchControl.EXPAND_MODE_OPEN);
      searchControl.addSearcher(websearch, options);

      // Set the Local Search center point
      localSearch.setCenterPoint("<?php echo $location; ?>");

		// tell the searcher to draw itself and tell it where to attach
      searchControl.draw(document.getElementById("searchcontrol"));

      // execute an inital search
      searchControl.execute("<?php echo $searchstring; ?>");

		function updateList(){
			$('.gsc-results').each(function(){
				$(this).replaceWith('<ul class="gsc-results gsc-webResult listview" data-inset="true">' + $(this).html() + '</ul>');
			});
			$('.gsc-result').each(function(){
				$(this).replaceWith('<li>' + $(this).html() + '</li>');
			});
			$('.gsc-expansionArea').each(function(){
				$(this).replaceWith($(this).html());
			});
			$('.gsc-cursor-box').clone(true).appendTo('.gsc-resultsRoot');
			$('.gsc-resultsRoot').addClass('gsc-expansionArea');
			$('ul.listview .gsc-cursor-box').remove();
			$('ul.listview').listview();
		}
		setTimeout(function(){
			$('.gsc-all-results').trigger('click');
			searchControl.draw(document.getElementById("searchcontrol"));
		}, 500);
		setTimeout(function(){
			updateList();
			$('#searchcontrol').show();
//			$('input.gsc-input').get(0).type='search';
//			$('input.gsc-input').textinput();
		}, 1000);
		var search = new google.search.Search();
		$('.gsc-cursor-box .gsc-cursor-page').live('click', function(){
			$('#searchcontrol').hide();
//			alert(websearch.cursor.currentPageIndex);
			websearch.gotoPage($(this).html() - 1);
			searchControl.draw(document.getElementById("searchcontrol"));
			setTimeout(function(){
				updateList();
				$('#searchcontrol').show();
			}, 500);
		});
		
    }
    google.setOnLoadCallback(OnLoad);

    //]]>
    </script>
<?php echo CHtml::link(Yii::t('app', 'Go back'), array('userCar/view', 'id'=>$_GET['car_id']), array('data-role'=>'button', 'data-inline'=>'true', 'data-icon'=>'back', 'data-ajax'=>'false'));?>
<div id="searchcontrol" style="display: none;">Loading</div>

*/?>