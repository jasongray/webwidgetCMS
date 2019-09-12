<?php $this->Html->pageClass = 'dashboard';?>
<?php $this->Html->pageTitle = 'Dashboard';?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php /*
				<div class="row row-bg"> <!-- .row-bg -->
					<div class="col-sm-6 col-md-3">
						<div class="statbox widget box box-shadow">
							<div class="widget-content">
								<div class="visual cyan">
									<i class="icon-shopping-cart"></i>
								</div>
								<div class="title"><?php echo __('Current & Abandoned Carts');?></div>
								<div class="value"><?php echo $carts;?></div>
								<?php echo $this->Html->link('View More <i class="pull-right icon-angle-right"></i>', array('controller' => 'cart', 'action' => 'index', 'admin' => 'admin'), array('class' => 'more', 'escape' => false));?>
							</div>
						</div> <!-- /.smallstat -->
					</div> <!-- /.col-md-3 -->

					<div class="col-sm-6 col-md-3">
						<div class="statbox widget box box-shadow">
							<div class="widget-content">
								<div class="visual green">
									<i class="icon-bullhorn"></i>
								</div>
								<div class="title"><?php echo __('Feedback');?></div>
								<div class="value">714</div>
								<a href="javascript:void(0);" class="more">View More <i class="pull-right icon-angle-right"></i></a>
							</div>
						</div> <!-- /.smallstat -->
					</div> <!-- /.col-md-3 -->

					<div class="col-sm-6 col-md-3 hidden-xs">
						<div class="statbox widget box box-shadow">
							<div class="widget-content">
								<div class="visual yellow">
									<i class="icon-dollar"></i>
								</div>
								<div class="title"><?php echo __('Total Sales');?></div>
								<div class="value">$42,512.61</div>
								<a href="javascript:void(0);" class="more">View More <i class="pull-right icon-angle-right"></i></a>
							</div>
						</div> <!-- /.smallstat -->
					</div> <!-- /.col-md-3 -->

					<div class="col-sm-6 col-md-3 hidden-xs">
						<div class="statbox widget box box-shadow">
							<div class="widget-content">
								<div class="visual red">
									<i class="icon-user"></i>
								</div>
								<div class="title"><?php echo __('Outstanding Orders');?></div>
								<div class="value">2 521 719</div>
								<a href="javascript:void(0);" class="more">View More <i class="pull-right icon-angle-right"></i></a>
							</div>
						</div> <!-- /.smallstat -->
					</div> <!-- /.col-md-3 -->
					
				</div>
				*/?>
				<?php if ($update) { ?>
				<?php echo $this->element('update-available-dashboard');?>
				<?php } ?>
				<!--=== Blue Chart ===-->
				<div class="row">
					<div class="col-md-12">
						<div class="widget box">
							<div class="widget-header">
								<h4><i class="fas fa-bars"></i> Total Visits</h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="fas fa-angle-double-down"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content">
								<div id="chart_filled_blue" class="chart">
									<div class="ajax-loading">
										<i class="fas fa-spinner fa-pulse fa-3x"></i>
									</div>
								</div>
							</div>
							<div class="divider"></div>
							<div class="widget-content">
								<ul class="stats"> <!-- .no-dividers -->
									<li class="vcnt">
										<div class="ajax-loading">
											<i class="fas fa-spinner fa-pulse fa-lg"></i>
										</div>
										<strong></strong>
										<small><?php echo __('Total Views');?></small>
									</li>
									<li class="light hidden-xs tcnt">
										<div class="ajax-loading">
											<i class="fas fa-spinner fa-pulse fa-lg"></i>
										</div>
										<strong></strong>
										<small><?php echo __('Last 24 Hours');?></small>
									</li>
									<li class="ucnt">
										<div class="ajax-loading">
											<i class="fas fa-spinner fa-pulse fa-lg"></i>
										</div>
										<strong></strong>
										<small><?php echo __('Unique Users');?></small>
									</li>
									<li class="light hidden-xs tunt">
										<div class="ajax-loading">
											<i class="fas fa-spinner fa-pulse fa-lg"></i>
										</div>
										<strong></strong>
										<small><?php echo __('Last 24 Hours');?></small>
									</li>
								</ul>
							</div>
							<div class="divider"></div>
							<div class="widget-content">
								<ul class="stats no-dividers">
									<li class="circular-chart-inline">
										<div class="circular-chart" data-percent="<?php echo $this->Number->precision($svr['systemload'], 0);?>" data-size="90"><?php echo $this->Number->toPercentage($svr['systemload'], 2);?></div>
										<span class="description"><?php echo __('Server Load');?></span>
									</li>
									<li class="circular-chart-inline">
										<div class="circular-chart" data-percent="<?php echo $this->Number->precision(abs($svr['memoryuseage']/str_replace('kb', '', $svr['memorytotal'])*100));?>" data-size="90" data-bar-color="#e25856"><?php echo $this->Number->toReadableSize($svr['memoryuseage']);?></div>
										<span class="description"><?php echo __('Used RAM');?></span>
									</li>
									<li class="circular-chart-inline">
										<div class="circular-chart" data-percent="<?php echo $this->Number->precision(($svr['diskfreespace']/$svr['totaldiskspace'])*100);?>" data-size="90" data-bar-color="#8fc556"><?php echo $this->Number->toReadableSize($svr['diskfreespace']);?></div>
										<span class="description"><?php echo __('Disk Space');?></span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

<?php echo $this->Html->script(array('plugins/sparkline/jquery.sparkline.min', 'plugins/flot/jquery.flot.min', 'plugins/flot/jquery.flot.tooltip.min', 'plugins/flot/jquery.flot.resize.min', 'plugins/flot/jquery.flot.time.min', 'plugins/flot/jquery.flot.growraf.min', 'plugins/easy-pie-chart/jquery.easy-pie-chart.min', 'system'), array('inline' => false));?>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('plugins/flot/excanvas.min'), array('inline' => false));?>
<![endif]-->
<?php echo $this->Html->scriptBlock("
$(document).ready(function(){
	$.ajax({
		type: 'POST',
		url: '".$this->Html->url(array('controller' => 'reports', 'action' => 'ajax', 'admin' => 'admin', 'plugin' => false))."', 
		dataType: 'json',
		success: function(result) {
			$('#chart_filled_blue .ajax-loading').fadeOut('slow', function(){ $(this).remove; });
			var data1 = [
				{ label: 'Total clicks', data: result, color: App.getLayoutColorCode('blue') }
			];
			$.plot('#chart_filled_blue', data1, $.extend(true, {}, Plugins.getFlotDefaults(), {
				xaxis: {
					min: (new Date(".strftime('%Y,%m,1',(strtotime('12 months ago'))).")).getTime(),
					max: (new Date(".strftime('%Y,%m,1',(strtotime('now'))).")).getTime(),
					mode: 'time',
					tickSize: [1, 'month'],
					monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
					},
					grow: { active: true, growings:[ { stepMode: 'maximum' } ] }
				},
				grid: {
					hoverable: true,
					clickable: true
				},
				tooltip: true,
				tooltipOpts: {
					content: '%s: %y'
				}
			}))
		}
	});
	$.ajax({
		type: 'POST',
		url: '".$this->Html->url(array('controller' => 'visits', 'action' => 'ajax', 'admin' => 'admin', 'plugin' => false))."', 
		dataType: 'json',
		success: function(result) {
			for(var key in result) {
				if(result.hasOwnProperty(key)) {
					$('.stats .'+key+' strong').html(result[key]); 
					$('.stats .'+key+' .ajax-loading').fadeOut('slow');
				}
			}
		}
	});
});", array('inline' => false));  ?>