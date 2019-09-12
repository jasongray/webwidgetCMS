<?php echo $this->Html->css(array('datepicker'), array('block' => 'css'));?>
<div class="widget event-calendar">
	<?php if ($m['show_title'] == 1) { ?>
	<?php echo $this->Html->tag('h4', $m['title'], array('class' => 'widget_title'));?>
	<?php } ?>
	<div id="datepicker" class="calendar"></div>
	<?php echo $this->Html->link(__('View all events'), array('controller' => 'events', 'action' => 'index'), array('class' => 'eventlinks'));?>
</div>
<?php echo $this->Html->script(array('bootstrap-datepicker.min'), array('inline' => false));?>
<?php echo $this->Html->scriptBlock("
var date = new Date();	date.setDate(date.getDate());
$('#datepicker.calendar').datepicker({
	format: 'dd/mm/yyyy',
	todayHighlight: true,
	startDate: date,
});", array('inline' => false));?>