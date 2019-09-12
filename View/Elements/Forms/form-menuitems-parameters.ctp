<div class="tabbable box-tabs">
	<ul class="nav nav-tabs">
		<li><a href="#box_tab4" data-toggle="tab"><i class="icon-map-marker"></i> <?php echo __('Google Map');?></a></li>
		<li><a href="#box_tab3" data-toggle="tab"><i class="icon-paper-clip"></i> <?php echo __('Additional Parameters');?></a></li>
		<li><a href="#box_tab2" data-toggle="tab"><i class="icon-search"></i> <?php echo __('SEO');?></a></li>
		<li class="active"><a href="#box_tab1" data-toggle="tab"><i class="icon-link"></i> <?php echo __('URL');?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="box_tab1">
			<?php $_controller = (!empty($this->request->data['MenuItem']['controller']))? $this->request->data['MenuItem']['controller']: ''; ?>
			<?php echo $this->Form->input('controller', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Select Link'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $links, 'selected' => $_controller, 'empty' => ''));?>
			<?php echo $this->Form->input('action', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Select Page'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => $slugs, 'selected' => $_slug, 'empty' => '', 'type' => 'select'));?>
			<?php echo $this->Form->input('url', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Url'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
		</div>
		<div class="tab-pane" id="box_tab2">
			<?php echo $this->Form->input('page_title', array('div' => 'form-group', 'class' => 'form-control', 'label' => array('text' => __('Page Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>')); ?>
			<?php echo $this->Form->input('show_title', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Show Title'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
			<?php echo $this->Form->input('page_kw', array('div' => 'form-group', 'label' => array('text' => __('Page Keywords'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
			<?php echo $this->Form->input('page_meta', array('div' => 'form-group', 'label' => array('text' => __('Page Metadata'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'type' => 'textarea', 'class' => 'form-control', 'rows' => 10));?>
		</div>
		<div class="tab-pane" id="box_tab3">
			<?php echo $this->Form->input('MenuItem.params.leading_articles', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of leading articles'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
			<?php echo $this->Form->input('MenuItem.params.column_articles', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of columns'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
			<?php echo $this->Form->input('MenuItem.params.template', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Article Template'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'options' => array('list' => 'List', 'grid' => 'Grid')));?>
			<?php echo $this->Form->input('MenuItem.params.bloglimit', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of items to show'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
		</div>
		<div class="tab-pane" id="box_tab4">
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo __('Show Google Map');?></label>
				<div class="col-md-10">
					<?php echo $this->Form->input('MenuItem.params.show_map', array('div' => false, 'label' => array('text' => '', 'class' => 'checkbox'), 'class' => 'uniform', 'type' => 'checkbox')); ?>
				</div>
			</div>
			<?php echo $this->Form->input('MenuItem.params.lat', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Latitude'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
			<?php echo $this->Form->input('MenuItem.params.lng', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Longitude'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
			<span class="span12"><?php echo __('OR');?></span>
			<?php echo $this->Form->input('MenuItem.params.address_string', array('div' => 'form-group', 'class' => 'form-control input-width-xxlarge', 'label' => array('text' => __('Full Address'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="controls">', 'after' => '</div>'));?>
		</div>
	</div>
</div>