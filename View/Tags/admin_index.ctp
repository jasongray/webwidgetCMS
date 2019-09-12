<?php $this->Html->pageClass = 'tags';?>
<?php $this->Html->pageTitle = __('Manage Tags');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Tags'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Article Tags', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon">Id</th>
						<th><?php echo $this->Paginator->sort('tag');?></th>
						<th><?php echo __('Count');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($tags as $t){ ?>
					<tr>
						<td><?php echo $t['Tag']['id'];?></td>
						<td><?php echo $t['Tag']['tag'];?></td>
						<td><?php echo $t['Tag']['count'];?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $t['Tag']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the %s tag?'), $t['Tag']['tag']))); ?>
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