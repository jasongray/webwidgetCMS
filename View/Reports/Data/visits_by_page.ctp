<?php $this->Html->pageClass = 'reports';?>
<?php $this->Html->pageTitle = __('Reports - Visits by Page');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Reports'), array('controller' => 'reports', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Visits by Day'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-6">
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
				<div id="chart_pie" class="chart"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-edit"></i> <?php echo __('Page Count', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Page');?></th>
						<th class="icon"><?php echo  __('Visits');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($data as $d){ ?>
					<tr>
						<td><?php echo $d['visits']['page']; ?></td>
						<td><?php echo $d[0]['page_count']; ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
$cnt = 0; 
foreach($data as $d){
	$cnt = $cnt + $d[0]['page_count'];
}

$str = array();
for($i=0;$i<count($data);$i++) {
	if ($this->Number->precision($data[$i][0]['page_count']/$cnt*100, 0) != 0) {
		$str[] = 'd_donut['.$i.'] = { label: \'/'.str_replace($this->webroot, '', $data[$i]['visits']['page']).'\', data: '.$this->Number->precision($data[$i][0]['page_count']/$cnt*100, 0).' }';
	}
}
?>
<?php echo $this->Html->script(array('plugins/sparkline/jquery.sparkline.min', 'plugins/flot/jquery.flot.min', 'plugins/flot/jquery.flot.tooltip.min', 'plugins/flot/jquery.flot.resize.min', 'plugins/flot/jquery.flot.time.min', 'plugins/flot/jquery.flot.growraf.min', 'plugins/easy-pie-chart/jquery.easy-pie-chart.min'), array('inline' => false));?>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('plugins/flot/excanvas.min'), array('inline' => false));?>
<![endif]-->
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){
var d_donut = [];
".implode("\n", $str)."

$.plot('#chart_pie', d_donut, $.extend(true, {}, Plugins.getFlotDefaults(), {
	series: {
		pie: {
			show: true,
			radius: 1,
			label: {
				show: true
			}
		}
	},
	grid: {
		hoverable: true
	},
	tooltip: true,
	tooltipOpts: {
		content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
		shifts: {
			x: 20,
			y: 0
		}
	}
}));
console.log(d_donut);
});", array('inline' => false))?>