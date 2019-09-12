<?php $this->Html->pageClass = 'roles';?>

<?php $this->Html->pageTitle = __('Manage User Roles');?>
<?php $this->Html->addCrumb(__('Roles'), array('controller' => 'roles', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Add Role'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Add Role');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->element('Forms/form-roles');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>