<?php echo $this->Form->input('params.category_id', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Category'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'multiple' => 'multiple', 'empty' => '', 'options' => $helper->getCategories()));?>
<?php echo $this->Form->input('params.template', array('div' => 'form-group', 'class' => 'select2-select-00 col-md-12 full-width-fix', 'label' => array('text' => __('Template'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>', 'empty' => '', 'options' => $helper->getTmpl()));?>
</hr>
<?php echo $this->Form->input('params.leading_articles', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of leading articles'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
<?php echo $this->Form->input('params.bloglimit', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of items to show'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>
<?php echo $this->Form->input('params.column_articles', array('div' => 'form-group', 'class' => 'form-control input-width-mini', 'label' => array('text' => __('Number of columns'), 'class' => 'col-md-2 control-label'), 'between' => '<div class="col-md-10">', 'after' => '</div>'));?>