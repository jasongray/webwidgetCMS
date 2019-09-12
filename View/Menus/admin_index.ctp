<?php $this->Html->pageClass = 'menus';?>
<?php $this->Html->pageTitle = __('Menu Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Menus'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Menu List', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Create Menu', '/admin/menus/add', array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo $this->Paginator->sort('title', __('Menu Title'));?></th>
						<th><?php echo $this->Paginator->sort('unique', __('Unique Name'));?></th>
						<th><?php echo __('Menu Items');?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($menus as $m){ ?>
					<tr>
						<td><?php echo $this->Html->link($m['Menu']['id'], array('action' => 'edit', $m['Menu']['id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $this->Html->link($m['Menu']['title'], array('action' => 'edit', $m['Menu']['id']), array('escape' => false));?></td>
						<td><?php echo $m['Menu']['unique']; ?></td>
						<td><?php echo $this->Html->link('<i class="icon-list"></li>', array('controller' => 'menuItems', 'action' => 'index', 'menu_id' => $m['Menu']['id'], 'admin' => 'admin'), array('escape' => false));?></td>
						<td><?php if ($m['Menu']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $m['Menu']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $m['Menu']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the %s menu?'), $m['Menu']['title']))); ?>
						</td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php echo $this->element('paginator');?>
			</div>
		</div>
	</div>
</div>