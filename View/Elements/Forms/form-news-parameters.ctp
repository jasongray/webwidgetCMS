<div class="tabbable box-tabs">
	<ul class="nav nav-tabs">
		<li><a href="#box_tab3" data-toggle="tab"><i class="icon-search"></i> <?php echo __('SEO');?></a></li>
		<li><a href="#box_tab2" data-toggle="tab"><i class="icon-search"></i> <?php echo __('Multimedia');?></a></li>
		<li class="active"><a href="#box_tab1" data-toggle="tab"><i class="icon-tag"></i> <?php echo __('General');?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="box_tab1">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo __('Feature Post');?></label>
				<div class="col-md-9">
					<?php echo $this->Form->input('featured', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo __('Publish');?></label>
				<div class="col-md-9">
					<?php echo $this->Form->input('published', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
				</div>
			</div>
			<?php echo $this->Form->input('category_id', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Category'), 'class' => 'col-md-3 control-label'), 'between' => '<div class="col-md-9">', 'after' => '</div>', 'empty' => '')); ?>
			<?php $_author = empty($this->data['News']['author'])? $this->Session->read('Auth.User.firstname') . ' ' . $this->Session->read('Auth.User.surname'): $this->data['News']['author'];?>
			<?php echo $this->Form->input('author', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Author'), 'class' => 'col-md-3 control-label'), 'between' => '<div class="col-md-9">', 'after' => '</div>', 'value' => $_author)); ?>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo __('Show Author');?></label>
				<div class="col-md-9">
					<?php echo $this->Form->input('show_author', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo __('Show Created Date');?></label>
				<div class="col-md-9">
					<?php echo $this->Form->input('show_created', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo __('Show Modified Date');?></label>
				<div class="col-md-9">
					<?php echo $this->Form->input('show_modified', array('div' => false, 'label' => false, 'class' => 'uniform', 'type' => 'checkbox')); ?>
				</div>
			</div>
			<?php $_date = empty($this->data['News']['start_publish'])? date('d-m-Y'): $this->data['News']['start_publish'];?>
			<?php echo $this->Form->input('start_publish', array('div' => 'form-group', 'class' => 'form-control datepicker', 'label' => array('text' => __('Start publish date'), 'class' => 'col-md-3 control-label'), 'between' => '<div class="col-md-9">', 'after' => '</div>', 'value' => $_date, 'type' => 'text'));?>
			<?php echo $this->Form->input('end_publish', array('div' => 'form-group', 'class' => 'form-control datepicker', 'label' => array('text' => __('End publish date'), 'class' => 'col-md-3 control-label'), 'between' => '<div class="col-md-9">', 'after' => '</div>', 'type' => 'text'));?>
			<?php echo $this->Form->input('tags', array('type' => 'text', 'div' => 'form-group', 'class' => 'tags-autocomplete', 'label' => array('text' => __('Tags'), 'class' => 'col-md-3 control-label'), 'between' => '<div class="col-md-9">', 'after' => '</div>'));?>
		</div>
		<div class="tab-pane" id="box_tab2">
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo __('Leading Image');?></label>
				<div class="col-md-9">
					<?php echo $this->Form->input('Image.file', array('type' => 'file', 'data-style' => 'fileinput', 'div' => false, 'label' => false));?>
					<div class="avatar col-md-5">
					<?php if(!empty($this->data['News']['image']) ) { 
						echo $this->Resize->image('articles'.DS.$this->data['News']['image'], 300, 300, true, array('alt' => ''));
						echo $this->Html->link('<span class="badge">X</span>', array('controller' => 'news', 'action' => 'removeImage', $this->data['News']['id'], 'admin' => 'admin', 'plugin' => false), array('escape' => false));
					}?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->input('image_credit', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Image Credit'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
		</div>
		<div class="tab-pane" id="box_tab3">
			<?php echo $this->Form->input('page_title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Page Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<?php echo $this->Form->input('page_kw', array('div' => 'form-group', 'label' => array('text' => __('Page Keywords'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
			<?php echo $this->Form->input('page_meta', array('div' => 'form-group', 'label' => array('text' => __('Page Metadata'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
		</div>
	</div>
</div>