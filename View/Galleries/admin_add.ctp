<?php $this->Html->pageClass = 'galleries';?>
<?php $this->Html->pageTitle = __('Create Gallery');?>
<?php $this->Html->addCrumb(__('Galleries'), array('controller' => 'galleries', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Create Gallery'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-camera"></i> <?php echo __('Add Gallery');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->Form->create('Gallery', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
				<?php echo $this->Form->input('name', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Gallery Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
				<div class="form-actions">
				<?php
					echo $this->Form->hidden('id');
					echo $this->Form->button('Next <i class="icon-long-arrow-right"></i>' , array('class'=>'btn btn-success right', 'div' => false, 'escape' => false)); 
					echo $this->Html->link('Cancel', array('controller' => 'galleries', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn'));
				?>	
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>