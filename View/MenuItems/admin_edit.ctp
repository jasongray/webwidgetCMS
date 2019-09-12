<?php $this->Html->pageClass = 'menu';?>
<?php $this->Html->pageTitle = __('Manage Menus');?>
<?php $this->Html->addCrumb(__('Menus'), array('controller' => 'menus', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb($menu_title, array('controller' => 'menuItems', 'action' => 'index', 'admin' => 'admin', 'menu_id' => $this->passedArgs['menu_id']));?>
<?php $this->Html->addCrumb(__('Edit Menu Item'));?>

<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<?php echo $this->Form->create('MenuItem', array('class' => 'form-horizontal row-border'));?>
<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Edit Menu Item');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-menuitems');?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-user"></i> <?php echo __('Parameters');?></h4>
			</div>
			<div class="widget-content">
				<div class="form-horizontal row-border">
				<?php echo $this->element('Forms/form-menuitems-parameters');?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
<?php echo $this->element('forms-js');?>
<div id="iconModal" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 id="myModalLabel1"><?php echo __('Sample Icons');?></h3>
			</div>
			<div class="modal-body">
				<?php echo $this->element('icons');?>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</div>
</div>