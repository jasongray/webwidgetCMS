<?php $this->Html->pageClass = 'reports';?>
<?php $this->Html->pageTitle = __('Reports - Visits by Day');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Reports'), array('controller' => 'reports', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Visits by Day'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-edit"></i> <?php echo __('Visits by Day', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div id="chart_filled_blue" class="chart"></div>
			</div>
		</div>
	</div>
</div>
<?php 
$str = array();
foreach($data as $g) {
	$str[] = '['.$g[0]['day'].'000, '.$g[0]['count'].']';
}
$min = $data[0][0]['day'].'000';
$cnt = count($data)-1;
$max = $data[$cnt][0]['day'].'000';
?>
<?php echo $this->Html->script(array('plugins/sparkline/jquery.sparkline.min', 'plugins/flot/jquery.flot.min', 'plugins/flot/jquery.flot.tooltip.min', 'plugins/flot/jquery.flot.resize.min', 'plugins/flot/jquery.flot.time.min', 'plugins/flot/jquery.flot.growraf.min', 'plugins/easy-pie-chart/jquery.easy-pie-chart.min'), array('inline' => false));?>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('plugins/flot/excanvas.min'), array('inline' => false));?>
<![endif]-->
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){

	// Sample Data
	var d1 = [".implode(',',$str)."];

	var data1 = [
		{ label: '".__('Visits by Day')."', data: d1, color: App.getLayoutColorCode('blue') }
	];

	$.plot('#chart_filled_blue', data1, $.extend(true, {}, Plugins.getFlotDefaults(), {
		xaxis: {
			min: '$min',
			max: '$max',
			mode: 'time',
			tickSize: [1, 'day'],
			tickLength: 0
		},
		series: {
			lines: {
				fill: true,
				lineWidth: 1.5
			},
			points: {
				show: true,
				radius: 2.5,
				lineWidth: 1.1
			}
		},
		grid: {
			hoverable: true,
			clickable: true
		},
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		}
	}));

});", array('inline' => false))?>