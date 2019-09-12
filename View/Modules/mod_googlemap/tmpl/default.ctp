<div class="map-wrapper clearfix <?php echo $m['class'];?>">
	<?php if ($m['show_title'] == 1) { ?>
	<div class="post_header">
		<?php echo $this->Html->tag('h1', $m['title']);?>
		<span class="title_divider"></span>
	</div>
	<?php } ?>
	<div id="map" data-lat="<?php echo $m['lat'];?>" data-lng="<?php echo $m['lng'];?>" data-layout="<?php echo $m['layout'];?>" data-zoom="<?php echo (empty($m['zoom']))? 15: $m['zoom'];?>" data-colour="<?php echo (empty($m['colour']))? '#2d5c88': $m['colour'];?>" style="height:<?php echo (empty($m['height']))? 400: $m['height'];?>px;"></div>
</div>

<?php echo $this->Html->scriptBlock('
function showMap () {
  var map = new google.maps.Map(document.getElementById("map"), {
    center: {lat: $("#map").data("lat"), lng: $("#map").data("lng")},
    zoom: $("#map").data("zoom"),
  });

  var mrkr = new google.maps.Marker({
    map: map,
    position: {lat: $("#map").data("lat"), lng: $("#map").data("lng")}
  });
  
  if ($("#map").data("layout") == 2){ 
    map.setMapTypeId("satellite");
  }
  if ($("#map").data("layout") == 3){ 
    var s = [{ stylers: [{ hue: $("#map").data("colour")}, {saturation: -80}, {visibility: "simplified"}, {gamma: .3}] }];
    var t = new google.maps.StyledMapType(s, { name: "Grayscale" });
    map.mapTypes.set("styled_map", t);
    map.setMapTypeId("styled_map");
  }

}', array('inline' => false));?>
<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyAmSUXJB61NpZWM1KOfe092LOGNu-QqwMc&callback=showMap', array('inline' => false));?>