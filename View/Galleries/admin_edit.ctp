<?php $this->Html->pageClass = 'galleries';?>
<?php $this->Html->pageTitle = __('Edit Gallery');?>
<?php $this->Html->addCrumb(__('Galleries'), array('controller' => 'galleries', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb(__('Edit Gallery'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-7">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Edit Gallery');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->Form->create('Gallery', array('class' => 'form-horizontal row-border', 'type' => 'file'));?>
				<?php echo $this->Form->input('name', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Gallery Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
				<?php echo $this->Form->input('description', array('div' => 'form-group', 'label' => array('text' => __('Description'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control wysiwyg', 'rows' => 10));?>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Publish');?></label>
					<div class="col-md-10">
						<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
					</div>
				</div>
				<div class="form-actions">
				<?php
					echo $this->Form->hidden('id');
					echo $this->Form->button('Save' , array('class'=>'btn btn-success right', 'div' => false, 'escape' => false)); 
					echo $this->Html->link('Cancel', array('controller' => 'galleries', 'action' => 'cancel', 'admin' => 'admin', 'plugin' => false), array('class' => 'btn'));
					if(!empty($this->data['Gallery']['id'])){
						echo $this->Html->link('Delete', array('controller' => 'galleries', 'action' => 'delete', 'admin' => 'admin', 'plugin' => false, $this->data['Gallery']['id']), array('class' => 'btn btn-grey'));
					}
				?>	
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-upload"></i> <?php echo __('Uploader');?></h4>
			</div>
			<div class="widget-content">
				<div id="upload-wrapper"></div>
				<?php 
				$max_upload = (int)(ini_get('upload_max_filesize'));
				$max_post = (int)(ini_get('post_max_size'));
				$memory_limit = (int)(ini_get('memory_limit'));
				$upload_mb = min($max_upload, $max_post, $memory_limit);?>
				<small>(Max file size: <?php echo $upload_mb.'MB';?>)</small>
				<div id="uploaded"></div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-camera"></i> <?php echo __('Images');?></h4>
			</div>
			<div class="widget-content">
				<?php echo $this->element('Forms/form-gallery-uploadr');?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('forms-js');?>