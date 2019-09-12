<?php $memb = $this->requestAction(array('controller' => 'activityLogs', 'action' => 'recentmembers', 'admin' => 'admin', 'plugin' => false));?>
				<li class="dropdown hidden-xs hidden-sm">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="icon-warning-sign"></i>
						<?php if(count($memb) > 0){ ?>
							<span class="badge"><?php echo count($memb);?></span>
						<?php } ?>
					</a>
					<?php if(count($memb) > 0){ ?>
					<ul class="dropdown-menu extended notification">
						<li class="title">
							<p>You have <?php echo count($memb);?> new notification<?php if(count($memb)!=1){ echo 's';}?></p>
						</li>
						<?php foreach ($memb as $l) { ?>
						<li>
							<a href="<?php echo $this->Html->url(array('controller' => 'activityLogs', 'action' => 'view', 'admin' => 'admin', 'plugin' => false, $l['ActivityLog']['id']));?>">
								<?php $label = (!empty($l['ActivityLog']['type']))? 'label-'.$this->Xhtml->iconreplace($l['ActivityLog']['type']): '';?>
								<span class="label <?php echo $label;?>"><i class="<?php echo $this->Xhtml->iconme($l['ActivityLog']['description']);?>"></i></span>
								<span class="message"><?php echo $this->Xhtml->trim($l['ActivityLog']['description'], 30);?>...</span>
								<span class="time"><?php echo $this->Time->timeAgoInWords($l['ActivityLog']['created']);?></span>
							</a>
						</li>
						<?php } ?>
						<li class="footer">
							<?php echo $this->Html->link(__('View all'), array('controller' => 'activityLogs', 'action' => 'members', 'admin' => 'admin', 'plugin' => false));?>
						</li>
					</ul>
					<?php } else { ?>
						<ul class="dropdown-menu extended notification">
							<li class="title">
								<p><?php echo __('No new notifications');?></p>
							</li>
							<li class="footer">
								<?php echo $this->Html->link(__('View all'), array('controller' => 'activityLogs', 'action' => 'members', 'admin' => 'admin', 'plugin' => false));?>
							</li>
						</ul>	
					<?php } ?>
				</li>