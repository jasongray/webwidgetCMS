<?php $this->Html->pageClass = 'comments';?>
<?php $this->Html->pageTitle = __('Comments');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Comments'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-edit"></i> <?php echo __('Comments', true);?></h4>
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
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo $this->Paginator->sort('name', __('Comment Author'));?></th>
						<th>&nbsp;</th>
						<th><?php echo $this->Paginator->sort('news_id', __('News Article'));?></th>
						<th class="icon"><?php echo  __('Approved');?></th>
						<th class="icon"><?php echo  __('Spam Score');?></th>
						<th><?php echo $this->Paginator->sort('created', __('Date Posted'));?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($comments as $p){ ?>
					<tr>
						<td><?php echo $this->Html->link($p['Comment']['id'], array('action' => 'view', $p['Comment']['id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $this->Html->link($p['Comment']['name'], array('action' => 'view', $p['Comment']['id']), array('escape' => false));?></td>
						<td><?php if ($p['Comment']['viewed'] == 0){ echo '<span class="label label-info">'.__('Unread').'</span>';} ?></td>
						<td><?php echo $p['News']['title'];?></td>
						<td><?php if ($p['Comment']['status'] == 1){ echo '<span class="label label-success">'.__('Approved').'</span>'; } else { echo '&nbsp;';} ?></td>
						<td><?php if($p['Comment']['points'] > 0){ echo '<span class="label label-default">'; } else { echo '<span class="label label-danger">'; }?><?php echo $p['Comment']['points'];?><?php echo '</span>';?></td>
						<td><?php echo date('d-m-Y H:i', strtotime($p['Comment']['created']));?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'view', $p['Comment']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $p['Comment']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the comment %s?'), $p['Comment']['id']))); ?>
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