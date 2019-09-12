<?php $this->Html->pageClass = 'modules';?>
<?php $this->Html->pageTitle = __('Add Module');?>
<?php $this->Html->addCrumb(__('Modules'), array('controller' => 'modules', 'action' => 'index', 'admin' => 'admin', 'plugin' => false));?>
<?php $this->Html->addCrumb(__('Add Module'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('Module', array('class' => 'form-horizontal row-border'));?>
<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Select Module', true);?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
					<?php echo $this->Form->input('module_file', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Module'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $module_files, 'empty' => '')); ?>
					<?php echo $this->Form->input('position', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Position'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $positions, 'empty' => '')); ?>
					<div class="form-actions">
					<?php
						echo $this->Form->hidden('id');
						echo $this->Form->submit('Next', array('class'=>'btn btn-primary', 'div' => false)); 
						echo $this->Html->link('Cancel', array('controller' => 'modules', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn btn-orange'));
					?>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
<?php echo $this->element('forms-js');?>