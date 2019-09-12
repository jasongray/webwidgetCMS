<?php $this->Html->pageClass = 'slideshows';?>
<?php $this->Html->pageTitle = __('Slideshow Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Slideshows'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-picture"></i> <?php echo __('Slideshows');?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Add Image', array('controller' => 'slideshows', 'action' => 'add', 'admin' => 'admin'), array('class'=>'btn btn-success', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo $this->Paginator->sort('name', __('Name'));?></th>
						<th colspan="2"><?php echo __('Ordering');?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($slideshows as $s) { ?>
					<tr>
						<td><?php echo $this->Html->link($s['Slideshow']['id'], array('action' => 'edit', $s['Slideshow']['id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $this->Html->link($s['Slideshow']['name'], array('action' => 'edit', $s['Slideshow']['id']), array('escape' => false)); ?></td>
						<td><?php echo $this->Html->link('<i class="icon-arrow-up"></i>', array('controller' => 'slideshows', 'action' => 'orderup', $s['Slideshow']['id'], 'admin' => 'admin'), array('escape'=>false));?></td>
						<td><?php echo $this->Html->link('<i class="icon-arrow-down"></i>', array('controller' => 'slideshows', 'action' => 'orderdown', $s['Slideshow']['id'], 'admin' => 'admin'), array('escape'=>false));?></td>
						<td><?php if ($s['Slideshow']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { echo '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $s['Slideshow']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $s['Slideshow']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the %s slideshow?'), $s['Slideshow']['name']))); ?>
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