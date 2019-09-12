<?php $this->Html->pageClass = 'carts';?>
<?php $this->Html->pageTitle = __('User Shopping Carts');?>
<?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
<?php $this->Html->addCrumb(__('Carts'));?>
<?php echo $this->element('crumb-div');?>
<?php echo $this->element('page-header');?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('User Shopping Carts');?></h4>
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
						<th><?php echo __('Cart Time');?></th>
						<th><?php echo __('Products in Cart');?>
						<th><?php echo $this->Paginator->sort('created');?></th>
						<th><?php echo $this->Paginator->sort('modified');?></th>
						<th class="actions"><?php __('Actions');?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($carts as $c) { ?>
					<tr>
						<td><?php echo $this->Time->timeAgoInWords($c['Cart']['modified']);?>
						<td>
							<?php if (!empty($c['CartItems'])) { ?>
							<ul>
							<?php foreach ($c['CartItems'] as $_c) { ?>
								<li><?php echo $_c['Product']['qty'];?>x <?php echo $_c['Product']['prdLineName']. ' ' . $_c['Product']['prdCollection'] . ' ' . $_c['Product']['prdTitle'];?> <?php echo $_c['Product']['prdSize'];?> <?php echo $_c['Product']['prdColour'];?> <span class="pull-right"><?php echo $this->Number->currency($_c['Product']['qty'] * $_c['Product']['prdSellPrice'], 'AUD');?></span></li>
							<?php } ?>
							</ul>
							<?php } ?>
						</td>
						<td><?php echo $c['Cart']['created'];?></td>
						<td><?php echo $c['Cart']['modified'];?></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'delete', $c['Cart']['session_id']), array('escape' => false, 'class' => 'bs-tooltip trash', 'data-original-title' => 'Delete', 'data-alert-msg' => __('Are you sure you want to delete this cart?'))); ?>
						</td>
					<?php } ?>
					</tbody>
				</table>
				<?php echo $this->element('paginator');?>
			</div>
		</div>
	</div>
</div>