<?php
/*
  Plugin Name: MK Google Directions
  Plugin URI: https://manojranawpblog.wordpress.com/
  Description: MK Google Direction uses Google Directions API. It enables use of Google Directions in your WordPress blog. It also give details of distance between two locations and also shows driving direction between two places. Use shortcode [MKGD] in page/post to use this plugin
  Version: 2.2.2
  Author: Manoj Kumar
  Author URI: https://manojranawpblog.wordpress.com/
  Tags: Google Directions, Google Distance Calculator, Google Distance
 */

global $wp_version;

// Wordppress Version Check
if (version_compare($wp_version, '3.5', '<')) {
  exit($exit_msg . " Please upgrade your wordpress.");
}


/*
 * Add Stylesheet & Scripts for the plugin
 */

add_action('wp_enqueue_scripts', 'mkgd_scripts');

function mkgd_scripts() {
  $google_api_js = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&language=' . get_option('mkgd_language', 'en');
  wp_enqueue_script('mkgd-google-map-places', $google_api_js, array('jquery'));
  wp_register_style('mkgd-css', plugins_url('/css/mkgd-styles.css', __FILE__));
  wp_enqueue_style('mkgd-css');
}

/*
 * Add Footer Content
 */

add_action('wp_footer', 'mkgd_footer');

function mkgd_footer() {
  wp_enqueue_script('mkgd-google-map', plugins_url('/js/mkgd-google-map.js', __FILE__), array('jquery'));
  ?>
  <script type="text/javascript">
    jQuery("#btnMkgdSubmit").click(function() {
      var start = document.getElementById('origin').value;
      var end = document.getElementById('destination').value;
      if (start == "" || end == "") {
        alert("Please enter start and end points of your destination.");
        return false;
      }
      jQuery('#directions').html('<center><br/><img src="<?php echo plugins_url('mk-google-directions/images/loader.gif') ?>" alt="Loading Directions" title="Loading Directions"/></center>');
      jQuery.post('<?php echo plugins_url('/mkgd-ajax-handler.php', __FILE__); ?>', {origin: start, destination: end, language: '<?php echo get_option('mkgd_language', 'en'); ?>', units: '<?php echo get_option('mkgd_units', 'metric'); ?>'}, function(data) {
        jQuery('#directions').html(data);
      });
    });
  </script>
  <script type="text/javascript">
    /*
     * Load the google map
     */
    function initialize() {
      directionsDisplay = new google.maps.DirectionsRenderer();
      var chicago = new google.maps.LatLng(<?php echo get_option('mkgd_latitude', '43.6525'); ?>, <?php echo get_option('mkgd_longitude', '-79.3816667'); ?>);
      var mapOptions = {
        zoom: 7,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: chicago
      }
      map = new google.maps.Map(document.getElementById('mkgd-map-canvas'), mapOptions);
      directionsDisplay.setMap(map);
    }

  </script>
  <?php
}

/*
 * Initialize the map
 */

function mkgd_initialize() {
  $output = "";

  $output .= '<style>
    #mkgd-map-canvas{
      width: ' . get_option("mkgd_width", "500") . 'px;
      height: ' . get_option("mkgd_height", "500") . 'px;
    }
  </style>';

  $output .= '<div id="mkgd-wrap"><ul class="mkgd-form">';
  if (get_option('mkgd_show_start_point') == 1) {
    $output .= '<input id = "origin" name = "origin" value = "' . get_option('mkgd_default_start_point') . '" type = "hidden"/>';
  } else {
    $output .= '<li><label for = "origin">' . __("Origin") . '</label><input id = "origin" name = "origin" value = "' . get_option('mkgd_default_start_point') . '" type = "text" size = "50" /></li>';
  }

  $output .= '<li>
        <label for="destination">' . __("Destination") . '</label>
        <input id="destination" name="destination" type="text" size="50" />
      </li>
      <li>
        <input type="button" onclick="calcRoute();" name="btnMkgdSubmit" id="btnMkgdSubmit" value="' . __("Get Directions") . '"/>
      </li>
    </ul><!-- End .mkgd-form -->
    <div id="mkgd-map-canvas"></div><!-- End #mkgd-map-canvas -->
    <div id="directions"></div><!-- End #directions -->
  </div><!-- End #mkgd-wrap -->';
  return $output;
}

/*
 * Add Settings link
 */

function mkgd_settings_link($links) {
  $settings_link = '<a href="admin.php?page=mkgdAdminPage">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mkgd_settings_link');


/*
 * Add Shortcode Support
 */

function mkgd_shortcode($atts) {
  return mkgd_initialize();
}

add_shortcode('MKGD', 'mkgd_shortcode'); // Add shortcode [MKGD]

/*
 * Include Admin
 */
require_once 'mkgd-admin.php';


