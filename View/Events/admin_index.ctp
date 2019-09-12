<?php $this->Html->pageClass = 'events';?>
<?php $this->Html->pageTitle = __('Events');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Events'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-edit"></i> <?php echo __('Events', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Add Event', array('controller' => 'events', 'action' => 'add'), array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th>&nbsp;</th>
						<th><?php echo $this->Paginator->sort('title', __('Event Title'));?></th>
						<th><?php echo $this->Paginator->sort('datetime', __('Date & Time'));?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($events as $p){ ?>
					<tr>
						<td><?php echo $this->Html->link($p['Event']['id'], array('action' => 'edit', $p['Event']['id']), array('class'=>'edit-link'));?></td>
						<td><?php if ($p['Event']['featured'] == 1){ echo '<span class="label label-warning"><i class="icon icon-star"></i></span>'; } ?></td>
						<td><?php echo $this->Html->link($p['Event']['title'], array('action' => 'edit', $p['Event']['id']), array('escape' => false));?></td>
						<td><?php echo date('d-m-Y H:i', $p['Event']['datetime']); ?></td>
						<td><?php if ($p['Event']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $p['Event']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $p['Event']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the blog entry %s?'), $p['Event']['title']))); ?>
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