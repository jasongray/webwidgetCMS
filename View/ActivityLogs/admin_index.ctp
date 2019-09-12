<?php $this->Html->pageClass = 'logs';?>
<?php $this->Html->pageTitle = __('Activity Log');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Activity Log'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-edit"></i> <?php echo __('Activity Logs', true);?></h4>
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
						<th>&nbsp;</th>
						<th><?php echo __('Log Entry');?></th>
						<th><?php echo __('Created');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($logs as $l){ ?>
					<tr>
						<?php $label = (!empty($l['ActivityLog']['type']))? 'label-'.$this->Xhtml->iconreplace($l['ActivityLog']['type']): '';?>
						<td class="highlight">
							<span class="label <?php echo $label;?>">
								<i class="<?php echo $this->Xhtml->iconme($l['ActivityLog']['description']);?>"></i>
							</span>
						</td>
						<td><?php echo substr($l['ActivityLog']['description'], 0, 100); ?> 
						<?php if(!empty($l['User']['username'])){ echo '(' . __('User id') . ': ' . $this->Html->link($l['User']['username'], array('controller' => 'users', 'action' => 'edit', $l['User']['id'])) . ')'; }?></td>
						<td><?php echo $this->Time->timeAgoInWords($l['ActivityLog']['created']);?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'view', $l['ActivityLog']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $l['ActivityLog']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the comment %s?'), $l['ActivityLog']['id']))); ?>
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