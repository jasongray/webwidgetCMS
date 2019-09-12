<?php $this->Html->pageClass = 'menu';?>
<?php $this->Html->pageTitle = __('Manage Menus');?>
<?php $this->Html->addCrumb(__('Menus'), array('controller' => 'menus', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Add Menu'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Add Menu');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->element('Forms/form-menus');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>