<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.jqplot.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/jqplot.dateAxisRenderer.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/jqplot.pieRenderer.min.js');

?>
<script>
$(document).ready(function(){
  var expences_total=[
<?php
	$i = 0;
	foreach($expenses as $exp){
		if(!$i)
			$first = date('M d, Y', strtotime($exp->time)-8600*24);
		if($i++)
			echo ',
		';
		echo '[\''.$exp->time.'\','.$exp->value.']';
	}
?>
  ];
 var plot1 = $.jqplot('chart1', [expences_total], {
      title:'<?php echo Yii::t('app','Your expenses dynamic'); ?>', 
      gridPadding:{right:35},
	  grid:{
		background: '#F3F3F3',
		borderWidth: 0,
		shadow: false
	  },
      axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
		  tickOptions:{formatString:'%b %#d, %y'},
		  min:'<?php echo $first; ?>',
          tickInterval:'1 month'
        },
		yaxis:{
			show: true
		}
      },
      series:[{color:'#456F9A', lineWidth:4, markerOptions:{style:'circle', color: '#009900'}}]
  });

   var expences_categorized=[
<?php
	$i = 0;
	$value = 0;
	$last_category = 0;
	unset($exp);
	unset($category);
	if(count($expenses_cats)){
		foreach($expenses_cats as $exp){
			if($exp->category_id !== $last_category){
				if(isset($category)){
					if($i++)
						echo ',
					';
					echo '[\''.$category.'\','.$value.']';
				}
				$category = $exp->getCategory();
				$last_category = $exp->category_id;
				$value = $exp->value;
			}else{
				$value += $exp->value;
			}
		}
		if(isset($category))
			if($i)
				echo ',
			';
			echo '[\''.$category.'\','.$value.']';
	}
?>
  ];  
  var plot2 = $.jqplot ('chart2', [expences_categorized],
    {
      seriesDefaults: {
        renderer: $.jqplot.PieRenderer,
        rendererOptions: {
          // Turn off filling of slices.
          fill: true,
          showDataLabels: true,
		  dataLabels: 'label',
          // Add a margin to seperate the slices.
          sliceMargin: 0,
          // stroke the slices with a little thicker line.
          lineWidth: 1
        }
      },
      legend: { show:false, location: 'e' }
    }
  );
});
</script>
<div id="chart1"></div>
<h4><?php echo Yii::t('app','By expence type:'); ?></h4>
<div id="chart2"></div>
