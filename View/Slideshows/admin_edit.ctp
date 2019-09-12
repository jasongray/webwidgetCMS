<?php $this->Html->pageClass = 'slideshows';?>
<?php $this->Html->pageTitle = __('SlideShow Menus');?>
<?php $this->Html->addCrumb(__('Slideshows'), array('controller' => 'slideshows', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Edit Image'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-picture"></i> <?php echo __('Edit Image');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->element('Forms/form-slideshow');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>