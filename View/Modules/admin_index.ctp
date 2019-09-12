<?php $this->Html->pageClass = 'modules';?>
<?php $this->Html->pageTitle = __('Modules Manager');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Modules'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Modules');?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('Add Module', array('controller' => 'modules', 'action' => 'add', 'admin' => 'admin', 'plugin' => false), array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th><?php echo $this->Paginator->sort('title', __('Module Title'));?></th>
						<th class="icon"><?php echo  __('Position');?></th>
						<th colspan="2"><?php echo  __('Ordering');?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					if (isset($modules)) {
						$k = 1;
						for($i=0;$i<count($modules);$i++){ 
							$s = $modules[$i];
							$m = $modules[$i]['Module']['ordering'];
							$n = isset($modules[$i+1]['Module']['ordering'])? $modules[$i+1]['Module']['ordering']: 0;
							$p = isset($modules[$i-1]['Module']['ordering'])? $modules[$i-1]['Module']['ordering']: 0;
						?>
						<tr>
							<td><?php echo $this->Html->link($s['Module']['id'], '/admin/modules/edit/'.$s['Module']['id'], array('class'=>'edit-link'));?></td>
							<td><?php echo $this->Html->link($s['Module']['title'], '/admin/modules/edit/'.$s['Module']['id']);?></td>
							<td class="dates"><?php echo $s['Module']['position']; ?>&nbsp;</td>
							<td><?php if($m != 1){ echo $this->Html->link('<i class="icon-arrow-up"></i>', '/admin/modules/orderup/' . $s['Module']['id'], array('escape'=>false));}?></td>
							<td><?php if($i < count($modules) - 1 && $n != 1){ echo $this->Html->link('<i class="icon-arrow-down"></i>', '/admin/modules/orderdown/' . $s['Module']['id'], array('escape'=>false));}?></td>
							<td><?php if ($s['Module']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
							<td class="actions">
								<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $s['Module']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
								<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $s['Module']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete module %s?'), $s['Module']['title']))); ?>
							</td>
						</tr>
						<?php $k = 1 - $k;
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