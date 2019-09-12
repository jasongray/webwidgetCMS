<div class="tabbable box-tabs">
	<ul class="nav nav-tabs">
		<li><a href="#box_tab2" data-toggle="tab"><i class="icon-search"></i> <?php echo __('SEO');?></a></li>
		<li class="active"><a href="#box_tab1" data-toggle="tab"><i class="icon-tag"></i> <?php echo __('General');?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="box_tab1">
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo __('Event Image');?></label>
				<div class="col-md-10">
					<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
					<div class="avatar col-md-5">
					<?php if(!empty($this->data['Event']['image']) ) { 
						echo $this->Resize->image('events'.DS.$this->data['Event']['id'].DS.$this->data['Event']['image'], 300, 300, true, array('alt' => ''));
						echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'events', 'action' => 'removeImage', $this->data['Event']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
					}?>
					</div>
				</div>
			</div>
			<?php $_date = empty($this->data['Event']['datetime'])? date('Y-m-d H:i'): date('Y-m-d H:i', $this->data['Event']['datetime']);?>
			<?php echo $this->Form->input('datetime', array('div' => 'form-group', 'class' => 'form-control datetimepicker', 'label' => array('text' => __('Event date'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'text', 'value' => $_date));?>
			<?php $_date = empty($this->data['Event']['enddatetime'])? '': date('Y-m-d H:i', $this->data['Event']['enddatetime']);?>
			<?php echo $this->Form->input('enddatetime', array('div' => 'form-group', 'class' => 'form-control datetimepicker', 'label' => array('text' => __('Event end date'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'text', 'value' => $_date));?>
			<?php echo $this->Form->input('location', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Location'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo __('File');?></label>
				<div class="col-md-10">
					<?php echo $this->Form->input('File.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
					<div class="avatar col-md-12">
					<?php if(!empty($this->data['Event']['file']) ) { 
						echo $this->Html->link($this->data['Event']['file'] . ' <span class="badge">X</span>', array('controller' => 'events', 'action' => 'removeFile', $this->data['Event']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
					}?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->input('xlink', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('External Link'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<?php echo $this->Form->input('contact_number', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Contact Number'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
		</div>
		<div class="tab-pane" id="box_tab2">
			<?php echo $this->Form->input('page_title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Page Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<?php echo $this->Form->input('page_kw', array('div' => 'form-group', 'label' => array('text' => __('Page Keywords'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
			<?php echo $this->Form->input('page_meta', array('div' => 'form-group', 'label' => array('text' => __('Page Metadata'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
		</div>
	</div>
</div>