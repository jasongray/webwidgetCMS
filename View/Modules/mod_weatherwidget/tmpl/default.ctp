<div class="ads_block">
	<div class="weather-report">
		<div>
			<div class="weather-image"></div>
			<small><?php echo __('Weather Report');?></small>
			<b><?php echo $m['location'];?></b>
		</div>
		<div class="weather-temp"></div>
	</div>
	<div class="clearfix"></div>
</div>
<?php echo $this->Html->script(array('//cdnjs.cloudflare.com/ajax/libs/jquery.simpleWeather/3.1.0/jquery.simpleWeather.min.js'), array('inline' => false));?>
<?php echo $this->Html->scriptBlock("

	$.simpleWeather({
		location: '".$m['location']."',
		woeid: '',
		unit: 'c',
		success: function(weather) {
			$('.weather-image').html('<i class=\"wi wi-yahoo-'+weather.code+'\"></i>');
			$('.weather-temp').html('<strong>'+weather.temp+'&deg;'+weather.units.temp+'</strong>');
		},
		error: function(error) {
			console.log(error);
		}
	});

", array('inline' => false));?>