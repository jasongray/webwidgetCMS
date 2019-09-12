<?php $this->Html->pageClass = 'news';?>
<?php $this->Html->pageTitle = __('Edit Blog Post');?>
<?php $this->Html->addCrumb(__('Blog'), array('controller' => 'news', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('Edit Blog Post'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('News', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
<div class="row">
	<div class="col-md-7">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-file-alt"></i> <?php echo __('Article', true);?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-news');?>
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
				<?php echo $this->element('Forms/form-news-parameters');?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>