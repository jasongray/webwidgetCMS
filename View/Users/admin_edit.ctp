<?php $this->Html->pageClass = 'users';?>

<?php $this->Html->pageTitle = __('Manage Users');?>
<?php $this->Html->addCrumb(__('Users'), array('controller' => 'users', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Edit User'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('User', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<div class="row">
	<div class="col-md-7">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Edit User');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-users');?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-puzzle-piece"></i> <?php echo __('User Details');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-users-parameters');?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
<?php echo $this->element('forms-js');?>