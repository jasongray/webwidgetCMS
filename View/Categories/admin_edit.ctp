<?php $this->Html->pageClass = 'categories';?>

<?php $this->Html->pageTitle = __('Categories');?>
<?php $this->Html->addCrumb(__('Categories'), array('controller' => 'categories', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Edit Category'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Edit Category');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->element('Forms/form-categories');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>