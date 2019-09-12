<?php $logs = $this->requestAction(array('controller' => 'activityLogs', 'action' => 'recent', 'admin' => 'admin', 'plugin' => false));?>
<div class="sidebar-title">
	<span><?php echo __('Notifications');?></span>
</div>
<?php if(!empty($logs)){ ?>
<ul class="notifications"> 
	<?php foreach($logs as $l) { ?>
	<li style="">
		<div class="col-left">
			<?php $label = (!empty($l['ActivityLog']['type']))? 'label-'.$this->Xhtml->iconreplace($l['ActivityLog']['type']): '';?>
			<span class="label <?php echo $label;?>"><i class="<?php echo $this->Xhtml->iconme($l['ActivityLog']['description']);?>"></i></span>
		</div>
		<div class="col-right with-margin">
			<span class="message"><?php echo substr($l['ActivityLog']['description'], 0, 100); ?> </span>
			<span class="time"><?php echo $this->Time->timeAgoInWords($l['ActivityLog']['created']);?></span>
		</div>
	</li>
	<?php } ?>
</ul>
<?php } else { ?>
<ul class="notifications">
	<li><?php echo __('No new notifications');?></li>
</ul>
<?php } ?>
<div class="sidebar-widget align-center">
	<div class="btn-group">
		<?php echo $this->Html->link(__('View all notifications'), array('controller' => 'activityLogs', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
	</div>
</div>
