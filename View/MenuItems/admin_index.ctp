<?php $this->Html->pageClass = 'menus';?>
<?php $this->Html->pageTitle = __('Menu Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Menus'), array('controller' => 'menus', 'action' => 'index', 'admin' => 'admin'));?>
<?php $this->Html->addCrumb($menu_title);?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo $menu_title;?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Add Item', '/admin/menuItems/add/menu_id:'.$this->passedArgs['menu_id'], array('class'=>'btn btn-success', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo __('Menu Item');?></th>
						<th colspan="2"><?php echo __('Ordering');?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php $pt = 0;?>
					<?php for($i=0;$i<count($menuItems);$i++){ 
						$mi = $menuItems[$i]; ?>
					<tr>
						<td><?php echo $this->Html->link($mi['MenuItem']['id'], array('action' => 'edit', $mi['MenuItem']['id'], 'menu_id' => $this->passedArgs['menu_id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $this->Html->link($mi['MenuItem']['treename'], array('action' => 'edit', $mi['MenuItem']['id'], 'menu_id' => $this->passedArgs['menu_id']), array('escape' => false)); ?></td>
						
						<td><?php echo $this->Html->link('<i class="icon-arrow-up"></i>', '/admin/menuItems/orderup/' . $mi['MenuItem']['id'] . '/menu_id:' . $this->passedArgs['menu_id'], array('escape'=>false));?></td>
						<td><?php echo $this->Html->link('<i class="icon-arrow-down"></i>', '/admin/menuItems/orderdown/' . $mi['MenuItem']['id'] . '/menu_id:' . $this->passedArgs['menu_id'], array('escape'=>false));?></td>
						
						<td><?php if ($mi['MenuItem']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						
						
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $mi['MenuItem']['id'], 'menu_id' => $this->passedArgs['menu_id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $mi['MenuItem']['id'], 'menu_id' => $this->passedArgs['menu_id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the %s menu item?'), $mi['MenuItem']['treename']))); ?>
						</td>
					</tr>
					<?php $pt = $mi['MenuItem']['parent_id'];?>
					<?php } ?>
					</tbody>
				</table>
				<?php //echo $this->element('paginator');?>
			</div>
		</div>
	</div>
</div>