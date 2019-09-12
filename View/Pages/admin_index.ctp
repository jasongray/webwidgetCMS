<?php $this->Html->pageClass = 'pages';?>
<?php $this->Html->pageTitle = __('Content Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Content'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Content');?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Add Page', array('controller' => 'pages', 'action' => 'add', 'admin' => 'admin', 'plugin' => false), array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo $this->Paginator->sort('title', __('Page Title'));?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					if (isset($pages)) {
						foreach($pages as $p){ ?>
						<tr>
							<td><?php echo $this->Html->link($p['Page']['id'], array('controller' => 'pages', 'action' => 'edit', 'admin' => 'admin', 'plugin' => false, $p['Page']['id']), array('class'=>'edit-link'));?></td>
							<td><?php echo $this->Html->link($p['Page']['title'], array('controller' => 'pages', 'action' => 'edit', 'admin' => 'admin', 'plugin' => false, $p['Page']['id']));?></td>
							<td><?php if ($p['Page']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
							<td class="actions">
								<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $p['Page']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
								<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $p['Page']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the page %s?'), $p['Page']['title']))); ?>
							</td>
						</tr>
						<?php 
						} 
					}
					?>
					</tbody>
				</table>
				<?php echo $this->element('paginator');?>
			</div>
		</div>
	</div>
</div>