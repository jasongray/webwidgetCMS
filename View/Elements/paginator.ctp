<div class="row">
	<div class="table-footer">
		<div class="col-md-6">
			<?php echo $this->Paginator->counter(array('format' => __('Showing {:start} of {:end} of {:count} entries', true)));?>
		</div>
		<div class="col-md-6">
			<ul class="pagination">
				<?php echo $this->Paginator->prev('<i class="icon-arrow-left"></i> ' . __('Prev'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false));?>
				<?php echo $this->Paginator->numbers(array('tag' => 'li', 'class' => false, 'currentTag' => 'a', 'currentClass' => 'active', 'separator' => false, 'disabledTag' => 'a'));?>
				<?php echo $this->Paginator->next(__('Next') . ' <i class="icon-arrow-right"></i>', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a', 'escape' => false));?>
			</ul>
		</div>
	</div>
</div>