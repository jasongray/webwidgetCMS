<?php $this->Html->pageClass = 'permissions';?>
<?php $this->Html->pageTitle = 'Permissions';?>

<?php echo $this->Html->css('/acl/css/acl.css', array('block' => 'css'));?>
<?php echo $this->Html->script('/acl/js/jquery');?>
<?php echo $this->Html->script('/acl/js/acl_plugin');?>
<?php echo $this->Html->scriptBlock("var jQ164 = $.noConflict(true);")?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-pushpin"></i> <?php echo __('ACL Plugin');?></h4>
			</div>
			<div class="widget-content">
	
	<?php
	echo $this->Session->flash('plugin_acl');
	?>
	
	<?php

	if(!isset($no_acl_links))
	{
	    $selected = isset($selected) ? $selected : $this->params['controller'];
    
        $links = array();
        $links[] = $this->Html->link(__d('acl', 'Permissions'), array('controller' => 'aros', 'action' => 'index', 'admin' => 'admin', 'plugin' => 'acl'), array('class' => ($selected == 'aros' )? 'selected' : null));
        $links[] = $this->Html->link(__d('acl', 'Actions'), array('controller' => 'acos', 'action' => 'index', 'admin' => 'admin', 'plugin' => 'acl'), array('class' => ($selected == 'acos' )? 'selected' : null));
        
        echo $this->Html->nestedList($links, array('class' => 'acl_links'));
	}
	?>