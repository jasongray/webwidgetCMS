<?php $this->Html->pageClass = 'modules';?>
<?php $this->Html->pageTitle = __('Manage Modules');?>
<?php $this->Html->addCrumb(__('Modules'), array('controller' => 'modules', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('Edit Module'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('Module', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Module Information', true);?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-modules');?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Parameters');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
					<?php echo $this->Module->loadparamsform($this->data['Module']['module_file']);?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>