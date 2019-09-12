<?php $this->Html->pageClass = 'users';?>
<?php $this->Html->pageTitle = __('Manage Users');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Users'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('User List', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Create User', '/admin/users/add', array('class'=>'btn btn-warning', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th colspan="3"><?php echo $this->Paginator->sort('username', __('Name'));?></th>
						<th><?php echo $this->Paginator->sort('role_id', __('Role'));?></th>
						<th><?php echo $this->Paginator->sort('email', __('Email'));?></th>
						<th><?php echo $this->Paginator->sort('active', __('Active'));?></th>
						<th><?php echo $this->Paginator->sort('last_login', __('Last Login'));?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($users as $u){ ?>
						<?php if( empty($u['User']['avatar']) ) { 
							$_img = 'avatar-1.jpg';
						} else {
							$_img = $u['User']['avatar'];
						}
						$img = $this->Resize->image($_img, 30, 30, false, array('alt' => ''));
						?>
					<tr>
						<td><?php echo $this->Html->link($u['User']['id'], array('action' => 'edit', $u['User']['id']), array('class'=>'edit-link'));?></td>
						<td><?php echo $img;?></td>
						<td><?php echo $this->Html->link($u['User']['username'], array('action' => 'edit', $u['User']['id']), array('escape' => false));?></td>
						<td><?php echo $u['User']['firstname'];?> <?php echo $u['User']['surname'];?></td>
						<td><?php echo $u['Role']['name'];?></td>
						<td><?php echo $u['User']['email'];?></td>
						<td><?php if ($u['User']['active'] == 1){ echo '<span class="label label-success">'.__('Active').'</span>'; } else { '<span class="label label-warning">'.__('Deactive').'</span>';} ?></td>
						<td><?php if (!empty($u['User']['last_login'])){ echo $this->Time->timeAgoInWords($u['User']['last_login'], array('end' => '+1 year')); } else { echo __('Never'); } ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $u['User']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $u['User']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the %s role?'), $u['User']['username']))); ?>
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