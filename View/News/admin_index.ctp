<?php $this->Html->pageClass = 'news';?>
<?php $this->Html->pageTitle = __('Blog');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Blog'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-edit"></i> <?php echo __('Blog Posts', true);?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php echo $this->Html->link('New Blog Post', array('controller' => 'news', 'action' => 'add'), array('class'=>'btn btn-info', 'escape' => false))?>
				<table cellpadding="0" cellspacing="0" class="table table-hover table-striped">
					<thead>
					<tr>
						<th class="icon"><?php echo __('Id');?></th>
						<th class="icon"><i class="icon-star"></i></th>
						<th><?php echo $this->Paginator->sort('title', __('Blog Title'));?></th>
						<th><?php echo $this->Paginator->sort('category_id', __('Category'));?></th>
						<th><?php echo $this->Paginator->sort('author', __('Author'));?></th>
						<th class="icon"><?php echo  __('Published');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($news as $p){ ?>
					<tr id="post-<?php echo $p['News']['id'];?>">
						<td><?php echo $this->Html->link($p['News']['id'], array('action' => 'edit', $p['News']['id']), array('class'=>'edit-link'));?></td>
						<td class="ajax-featurepost"><?php if ($p['News']['featured'] == 1){ echo '<span class="label label-warning"><i class="icon-star"></i></span>'; } else { echo '<span class="label label-inverse"><i class="icon-star"></i></span>';} ?></td>
						<td><?php echo $this->Html->link($p['News']['title'], array('action' => 'edit', $p['News']['id']), array('escape' => false));?></td>
						<td><?php echo $p['Category']['title']; ?></td>
						<td><?php echo $p['News']['author']; ?></td>
						<td class="ajax-publishpost"><?php if ($p['News']['published'] == 1){ echo '<span class="label label-success">'.__('Published').'</span>'; } else { echo '<span class="label label-warning">'.__('Unpublished').'</span>';} ?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action' => 'edit', $p['News']['id']), array('escape' => false, 'class' => 'bs-tooltip', 'data-original-title' => 'Edit')); ?>
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $p['News']['id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => sprintf(__('Are you sure you want to delete the blog entry %s?'), $p['News']['title']))); ?>
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