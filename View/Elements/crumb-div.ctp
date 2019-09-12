				<div class="crumbs">
					<?php echo $this->Html->getCrumbList(array(
						'class' => 'breadcrumb',
						'id' => 'breadcrumbs',
						'separator' => ''
						), array(
					    'text' => '<i class="icon-home"></i> ' . __('Dashboard'),
					    'url' => array('controller' => 'users', 'action' => 'dashboard', 'admin' => 'admin', 'plugin' => false),
					    'escape' => false
					));?>
					<ul class="crumb-buttons">
						<li><?php echo $this->Html->link(sprintf('<i class="icon-signal"></i><span>%s</span>', __('Reports')), array('controller' => 'reports', 'action' => 'index'), array('escape' => false));?></li>
						<?php /* <li class="range"><a href="#">
							<i class="icon-calendar"></i>
							<span></span>
							<i class="icon-angle-down"></i>
						</a></li><?php */ ?>
					</ul>
				</div>