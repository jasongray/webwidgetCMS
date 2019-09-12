				<div class="page-header">
					<div class="page-title">
						<h3><?php echo $this->Html->pageTitle;?></h3>
						<?php echo $this->Xhtml->welcomemsg($this->Session->read('Auth.User.firstname'));?>
					</div>
					<?php /*
					<ul class="page-stats">
						<li>
							<div class="summary">
								<span><?php echo __('Recent Orders');?></span>
								<h3>17,561</h3>
							</div>
							<div id="sparkline-bar" class="graph sparkline hidden-xs">20,15,8,50,20,40,20,30,20,15,30,20,25,20</div>
						</li>
					</ul>
					*/ ?>
				</div>
				<?php /*
				<?php echo $this->Html->script(array('plugins/sparkline/jquery.sparkline.min.js'), array('inline' => false));?>
				<?php echo $this->Html->scriptBlock("
				$('#sparkline-bar').sparkline('html', {
				type: 'bar',
				height: '35px',
				zeroAxis: false,
				barColor: '#e25856'
				});
				", array('inline' => false))?>

				*/ ?>