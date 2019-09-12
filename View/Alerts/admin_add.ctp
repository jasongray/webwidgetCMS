<?php $this->Html->pageClass = 'alerts';?>

<?php $this->Html->pageTitle = __('Manage Alerts');?>
<?php $this->Html->addCrumb(__('Alerts'), array('controller' => 'alerts', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Add Alert'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-8">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-bullhorn"></i> <?php echo __('Add Alert');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->element('Forms/form-alerts');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>