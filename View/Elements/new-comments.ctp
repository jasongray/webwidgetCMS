<?php $cmts = $this->requestAction('comments/latest');?>
				<li class="dropdown hidden-xs hidden-sm">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="icon-envelope"></i>
						<?php if(count($cmts) > 0){ ?>
							<span class="badge"><?php echo count($cmts);?></span>
						<?php } ?>
					</a>
					<?php if(count($cmts) > 0){ ?>
					<ul class="dropdown-menu extended notification">
						<li class="title">
							<p>You have <?php echo count($cmts);?> new comment<?php if(count($cmts)!=1){ echo 's';}?></p>
						</li>
						<?php foreach ($cmts as $cm) { ?>
						<li>
							<a href="<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'view', 'admin' => 'admin', 'plugin' => false, $cm['Comment']['id']));?>">
								<?php if( empty($cm['User']['avatar']) ) { 
									$_img = 'avatar-1.jpg';
								} else {
									$_img = $cm['User']['avatar'];
								}
								$img = $this->Resize->image($_img, 40, 40, false, array('alt' => ''));?>
								<span class="photo">
									<?php echo $img;?>
								</span>
								<span class="subject">
									<span class="from"><?php if(!empty($cm['Comment']['name'])){ echo $cm['Comment']['name']; }else{ echo __('Anon'); }?></span>
									<span class="time"><?php echo $this->Time->timeAgoInWords($cm['Comment']['created']);?></span>
								</span>
								<span class="text">
									<?php echo $this->Xhtml->trim($cm['Comment']['details'], 30);?>...
								</span>
							</a>
						</li>
						<?php } ?>
						
					</ul>
					<?php } else { ?>
						<ul class="dropdown-menu extended notification">
							<li class="title">
								<p><?php echo __('No new comments');?></p>
							</li>
							<li class="footer">
								<?php echo $this->Html->link(__('View all'), array('controller' => 'comments', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
							</li>
						</ul>	
					<?php } ?>
				</li>