<?php $this->Html->pageClass = 'events';?>
<?php $this->Html->pageTitle = __('Edit Event');?>
<?php $this->Html->addCrumb(__('Events'), array('controller' => 'events', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('Edit Event'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('Event', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<div class="row">
	<div class="col-md-7">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-file-alt"></i> <?php echo __('Event Information', true);?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-event');?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-puzzle-piece"></i> <?php echo __('Event Details');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-event-parameters');?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>