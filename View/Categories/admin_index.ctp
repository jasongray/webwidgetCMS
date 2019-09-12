<?php $this->Html->pageClass = 'categories';?>
<?php $this->Html->pageTitle = __('Categories');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Categories'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Category List', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Add Category', array('controller' => 'categories', 'action' => 'add'), array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo $this->Paginator->sort('title', __('Category'));?></th>
						<th class="icon"><?php echo  __('Type');?></th>
						<th colspan="2"><?php echo __('Ordering');?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php $_level = 0; $_parent = 0;?>
					<?php foreach ($categories as $c){ ?>
					<?php 
					if ($c['Category']['parent_id'] != ''){ 
						if ($_parent != $c['Category']['parent_id']) {
							$_level++;
						}
					} else { 
						$_parent = 0; 
						$_level = 0;
					} 
					$_parent = $c['Category']['parent_id'];
					?>
					<tr>
						<td><?php echo $this->Html->link($c['Category']['id'], array('action' => 'edit', $c['Category']['id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $this->Html->link(str_repeat('__', $_level) . $c['Category']['title'], array('action' => 'edit', $c['Category']['id']), array('escape' => false));?></td>
						<td><?php if ($c['Category']['type'] == 1){ echo '<span class="label label-info">'.__('Product').'</span>'; }?>
							<?php if ($c['Category']['type'] == 2){ echo '<span class="label label-default">'.__('Blog').'</span>'; }?>
							<?php if ($c['Category']['type'] == 3){ echo '<span class="label label-primary">'.__('Content').'</span>'; }?>
							<?php if ($c['Category']['type'] == 4){ echo '<span class="label label-warning">'.__('Events').'</span>'; }?>
						</td>
						<td><?php echo $this->Html->link('<i class="icon-arrow-up"></i>', array('action' => 'orderup', $c['Category']['id'], 'type' => $c['Category']['type']), array('escape'=>false));?></td>
						<td><?php echo $this->Html->link('<i class="icon-arrow-down"></i>', array('action' => 'orderdown', $c['Category']['id'], 'type' => $c['Category']['type']), array('escape'=>false));?></td>
						<td><?php if ($c['Category']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $c['Category']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $c['Category']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete %s?'), $c['Category']['title']))); ?>
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