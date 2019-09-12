<?php $this->Html->pageClass = 'pages';?>
<?php $this->Html->pageTitle = __('Add Page');?>
<?php $this->Html->addCrumb(__('Content Manager'), array('controller' => 'pages', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('Add Page'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('Page', array('class' => 'form-horizontal row-border'));?>
<div class="row">
	<div class="col-md-7">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-file-alt"></i> <?php echo __('Content', true);?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-pages');?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-puzzle-piece"></i> <?php echo __('Parameters');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-pages-parameters');?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>