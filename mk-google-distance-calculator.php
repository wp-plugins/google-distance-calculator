<?php
/*
Plugin Name: MK Google Distance Calculator (MK-GDC)

Plugin URI: http://euclidesolutions.co.in

Version: 1.0

Author: Manoj Rana

Author URI: http://manojrana.co.cc

Description: This is plugin created to find the distance between two locations. It also displays a google map displaying the route map between the origin and the destination and also the driving locations between the two locations.

Tags: Google Maps Distance Calculator, Google Distance Calculator, Google Maps, Calculate Distance, Driving Directions, Get Directions
*/

global $wp_version;

// Wordppress Version Check
if(version_compare($wp_version,'3.0','<'))
{
	exit ($exit_msg." Please upgrade your wordpress.");
}

// Link javascript & css files
function mkinclude_files()
{
	wp_enqueue_style('mkdistance_calc_css', plugins_url('/css/styles.css',  __FILE__));
	wp_enqueue_style('google_map_css','http://code.google.com/apis/maps/documentation/javascript/examples/default.css');
	wp_enqueue_script('google_map_js', 'http://maps.google.com/maps/api/js?sensor=false');
	wp_enqueue_script('mkdistance_calc_js',plugins_url('/js/mk-get-distance.js',  __FILE__));
}
// Include files on page load
add_action('init', 'mkinclude_files');




function map_onload($lat,$lng)
{
 $output = '';
 $output .= '<script language="javascript" type="text/javascript">';
 $output .= 'window.onload=function(){initialize('.$lat.', '.$lng.');}';
 $output .= '</script>';
 echo $output;
}

function mkdistance_calculator($lat, $lng){
 map_onload($lat,$lng);
 $result = "";
 $result .= '<script language="javascript">
 function get_distance(form){
  from = form.place_from.value;
  to = form.place_to.value;
  calcRoute(from,to);
 }
</script>

<div id="mkgdc-wrap">
 <div id="map_canvas" style="position: relative;" ></div><!-- #map_canvas -->
<form action="" method="post" name="form1">
<table class="mkgdc-table">
<tr>
<td>From: </td><td><input type="text" name="place_from" class="txt" /></td>
<td>To: </td><td><input type="text" name="place_to" class="txt" /></td>
<td><input type="button" value="Get Distance" onclick="get_distance(this.form)"/></td>
</tr>
</table>
</form>

 <div id="distance"></div><!-- #distance -->
 <div id="steps"></div><!-- #steps -->
</div><!-- #mkgdc-wrap -->';
 return $result;

}


function mk_shortcode($atts){
	$lat = isset($atts['latitude'])?$atts['latitude']:43.6525;
	$lng = isset($atts['longitude'])?$atts['longitude']:-79.3816667;
	$result = mkdistance_calculator($lat, $lng);
	return $result;
}

// Add [MK-GDC] shortcode
add_shortcode("MK-GDC", "mk_shortcode");

?>