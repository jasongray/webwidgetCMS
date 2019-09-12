<?php $this->Html->pageClass = 'alerts';?>
<?php $this->Html->pageTitle = __('Manage Alertss');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Alertss'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-bullhorn"></i> <?php echo __('Alerts', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('<span></span> Create Alerts', array('controller' => 'alerts', 'action' => 'add', 'admin' => 'admin'), array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon">Id</th>
						<th><?php echo $this->Paginator->sort('title');?></th>
						<th><?php echo $this->Paginator->sort('end_date', __('Expires'));?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($alerts as $alert){ ?>
					<tr id="alert-<?php echo $alert['Alert']['id'];?>">
						<td><?php echo $this->Html->link($alert['Alert']['id'], array('action' => 'edit', $alert['Alert']['id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $this->Html->link($alert['Alert']['title'], array('action' => 'edit', $alert['Alert']['id']));?></td>
						<td><?php if(!empty($a['Alert']['end_date'])){ echo date('d-m-Y H:i:s', strtotime($a['Alert']['end_date']));}?></td>
						<td class="ajax-publishalert"><?php if ($alert['Alert']['publish'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { echo '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $alert['Alert']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $alert['Alert']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the %s alert?'), $alert['Alert']['title']))); ?>
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