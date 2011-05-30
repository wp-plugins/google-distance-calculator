<?php
/*
  Plugin Name: MK Google Distance Calculator (MK-GDC)

  Plugin URI: http://euclidesolutions.co.in

  Version: 1.1

  Author: Manoj Rana

  Author URI: http://manojrana.co.cc

  Description: This is plugin created to find the distance between two locations. It also displays a google map displaying the route map between the origin and the destination and also the driving locations between the two locations.

  Tags: Google Maps Distance Calculator, Google Distance Calculator, Google Maps, Calculate Distance, Driving Directions, Get Directions
 */

global $wp_version;

// Wordppress Version Check
if (version_compare($wp_version, '3.0', '<')) {
    exit($exit_msg . " Please upgrade your wordpress.");
}

// Link javascript & css files
function mkinclude_files($lang) {
    wp_enqueue_style('mkdistance_calc_css', plugins_url('/css/styles.css', __FILE__));
    wp_enqueue_style('google_map_css', 'http://code.google.com/apis/maps/documentation/javascript/examples/default.css');
    wp_enqueue_script('google_map_js', 'http://maps.google.com/maps/api/js?sensor=false&language=' . get_option('language'));
    wp_enqueue_script('mkdistance_calc_js', plugins_url('/js/mk-get-distance.js', __FILE__));
}

// Include files on page load
add_action('init', 'mkinclude_files');

function map_onload($lat, $lng) {
    $output = '';
    $output .= '<script language="javascript" type="text/javascript">';
    $output .= 'window.onload=function(){initialize(' . $lat . ', ' . $lng . ');}';
    $output .= '</script>';
    echo $output;
}

function mkdistance_calculator() {
    $lat = get_option('latitude');
    $lng = get_option('longitude');
    map_onload($lat, $lng);
    $result = "";
    $result .= '<script language="javascript">
 function get_distance(form){
  from = form.place_from.value;
  to = form.place_to.value;
  calcRoute(from,to);
 }
</script>

<div id="mkgdc-wrap">
 <div id="map_canvas" style="position: relative;width:' . get_option('map_width') . 'px;height:' . get_option('map_height') . 'px;margin:0px auto;border:solid 5px #003;" ></div><!-- #map_canvas -->
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

function mk_shortcode($atts) {
    $result = mkdistance_calculator();
    return $result;
}

// Add [MK-GDC] shortcode
add_shortcode("MK-GDC", "mk_shortcode");


/* ==============================================================================
 *
 *              Admin Section for the Plugin
 *
  ============================================================================== */

function mk_set_options() {
    add_option('latitude', '43.6525', 'Default Latitude');
    add_option('longitude', '-79.3816667', 'Default Longitude');
    add_option('language', 'en', 'Default Longitude');
    add_option('map_width', '500', 'Default Longitude');
    add_option('map_height', '300', 'Default Longitude');
}

function mk_unset_options() {
    delete_option('latitude');
    delete_option('longitude');
    delete_option('language');
    delete_option('map_width');
    delete_option('map_height');
}

register_activation_hook(__FILE__, 'mk_set_options');
register_deactivation_hook(__FILE__, 'mk_unset_options');

function mk_options_page() {
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-plugins"></div>
        <h2>MK-GDC Options Page</h2>
        <?php
        if ($_REQUEST['submit']) {
            mk_update_options();
        }
        mk_print_options_form();
        ?>
    </div>
    <?php
}

function mk_update_options() {
    $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] != "" ? $_REQUEST['lat'] : 43.6525  : 43.6525;
    update_option('latitude', $lat);

    $long = isset($_REQUEST['long']) ? $_REQUEST['long'] != "" ? $_REQUEST['long'] : -79.3816667  : -79.3816667;
    update_option('longitude', $long);

    $lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] != "" ? $_REQUEST['lang'] : 'en'  : 'en';
    update_option('language', $lang);

    $map_width = isset($_REQUEST['map_width']) ? $_REQUEST['map_width'] != "" ? $_REQUEST['map_width'] : 500  : 500;
    update_option('map_width', $map_width);

    $map_height = isset($_REQUEST['map_height']) ? $_REQUEST['map_height'] != "" ? $_REQUEST['map_height'] : 300  : 300;
    update_option('map_height', $map_height);
    echo '<div id="message" class="updated fade"><p><strong>Options Saved...</strong></p></div>';
}

function mk_print_options_form() {
    $default_latitude = get_option('latitude');
    $default_longitude = get_option('longitude');
    $default_language = get_option('language');
    $default_map_width = get_option('map_width');
    $default_map_height = get_option('map_height');
    ?>
    <form method="post">
        <table class="widefat" style="width: 600px;" >
            <thead>
                <tr>
                    <th colspan="2">To configure the MK-GDC Plugin update the following values</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="2">Add [MK-GDC] shortcode to the post/page.</th>
                </tr>
            </tfoot>
            <tbody>
            <tr>
                <td><label for="latitude">Latitude: </label></td>
                <td><input type="text" name="lat" size="30" value="<?php echo $default_latitude; ?>" /> <small>Default latitudes for the google map</small></td>
            </tr>
            <tr>
                <td><label for="longitude">Longitude: </label></td>
                <td><input type="text" name="long" size="30" value="<?php echo $default_longitude; ?>" /> <small>Default longitudes for the google map</small></td>
            </tr>
            <tr>
                <td><label for="language">Language: </label></td>
                <td>
                    <select name="lang" value="<?php echo $default_language; ?>" >
                        <option value="ar" <?php echo ($default_language == "ar") ? 'selected="selected"' : ''; ?> >Arabic</option>
                        <option value="eu" <?php echo ($default_language == "eu") ? 'selected="selected"' : ''; ?> >Basque</option>
                        <option value="bg" <?php echo ($default_language == "bg") ? 'selected="selected"' : ''; ?> >Bulgarian</option>
                        <option value="ca" <?php echo ($default_language == "ca") ? 'selected="selected"' : ''; ?> >Catalan</option>
                        <option value="cs" <?php echo ($default_language == "cs") ? 'selected="selected"' : ''; ?> >Czech</option>
                        <option value="da" <?php echo ($default_language == "da") ? 'selected="selected"' : ''; ?> >Danish</option>
                        <option value="en" <?php echo ($default_language == "en") ? 'selected="selected"' : ''; ?> >English</option>
                        <option value="de" <?php echo ($default_language == "de") ? 'selected="selected"' : ''; ?> >German</option>
                        <option value="el" <?php echo ($default_language == "el") ? 'selected="selected"' : ''; ?> >Greek</option>
                        <option value="es" <?php echo ($default_language == "es") ? 'selected="selected"' : ''; ?> >Spanish</option>
                        <option value="fa" <?php echo ($default_language == "fa") ? 'selected="selected"' : ''; ?> >Farsi</option>
                        <option value="fi" <?php echo ($default_language == "fi") ? 'selected="selected"' : ''; ?> >Finnish</option>
                        <option value="fil" <?php echo ($default_language == "fil") ? 'selected="selected"' : ''; ?>>Filipino</option>
                        <option value="fr" <?php echo ($default_language == "fr") ? 'selected="selected"' : ''; ?> >French</option>
                        <option value="gl" <?php echo ($default_language == "gl") ? 'selected="selected"' : ''; ?> >Galician</option>
                        <option value="gu" <?php echo ($default_language == "gu") ? 'selected="selected"' : ''; ?> >Gujarati</option>
                        <option value="hi" <?php echo ($default_language == "hi") ? 'selected="selected"' : ''; ?> >Hindi</option>
                        <option value="hr" <?php echo ($default_language == "hr") ? 'selected="selected"' : ''; ?> >Croatian</option>
                        <option value="hu" <?php echo ($default_language == "hu") ? 'selected="selected"' : ''; ?> >Hungarian</option>
                        <option value="id" <?php echo ($default_language == "id") ? 'selected="selected"' : ''; ?> >Indonesian</option>
                        <option value="iw" <?php echo ($default_language == "iw") ? 'selected="selected"' : ''; ?> >Hebrew</option>
                        <option value="ja" <?php echo ($default_language == "ja") ? 'selected="selected"' : ''; ?> >Japanese</option>
                        <option value="kn" <?php echo ($default_language == "kn") ? 'selected="selected"' : ''; ?> >Kannada</option>
                        <option value="ko" <?php echo ($default_language == "ko") ? 'selected="selected"' : ''; ?> >Korean</option>
                        <option value="lt" <?php echo ($default_language == "lt") ? 'selected="selected"' : ''; ?> >Lithuanian</option>
                        <option value="lv" <?php echo ($default_language == "lv") ? 'selected="selected"' : ''; ?> >Latvian</option>
                        <option value="ml" <?php echo ($default_language == "ml") ? 'selected="selected"' : ''; ?> >Malayalam</option>
                        <option value="mr" <?php echo ($default_language == "mr") ? 'selected="selected"' : ''; ?> >Marathi</option>
                        <option value="nl" <?php echo ($default_language == "nl") ? 'selected="selected"' : ''; ?> >Dutch</option>
                        <option value="nn" <?php echo ($default_language == "nn") ? 'selected="selected"' : ''; ?> >Norwegian Nynorsk</option>
                        <option value="no" <?php echo ($default_language == "no") ? 'selected="selected"' : ''; ?> >Norwegian</option>
                        <option value="or" <?php echo ($default_language == "or") ? 'selected="selected"' : ''; ?> >Oriya</option>
                        <option value="pl" <?php echo ($default_language == "pl") ? 'selected="selected"' : ''; ?> >Polish</option>
                        <option value="pt" <?php echo ($default_language == "pt") ? 'selected="selected"' : ''; ?> >PortuguesE</option>
                        <option value="pt-BR" <?php echo ($default_language == "pt-BR") ? 'selected="selected"' : ''; ?> >Portuguese(Brazil)</option>
                        <option value="pt-PT" <?php echo ($default_language == "pt-PT") ? 'selected="selected"' : ''; ?> >Portuguese(Portugal)</option>
                        <option value="rm" <?php echo ($default_language == "rm") ? 'selected="selected"' : ''; ?> >Romansch</option>
                        <option value="ro" <?php echo ($default_language == "ro") ? 'selected="selected"' : ''; ?> >Romanian</option>
                        <option value="ru" <?php echo ($default_language == "ru") ? 'selected="selected"' : ''; ?> >Russian</option>
                        <option value="sk" <?php echo ($default_language == "sk") ? 'selected="selected"' : ''; ?> >Slovak</option>
                        <option value="sr" <?php echo ($default_language == "sr") ? 'selected="selected"' : ''; ?> >Serbian</option>
                        <option value="sv" <?php echo ($default_language == "sv") ? 'selected="selected"' : ''; ?> >Swedish</option>
                        <option value="tl" <?php echo ($default_language == "tl") ? 'selected="selected"' : ''; ?> >Tagalog</option>
                        <option value="ta" <?php echo ($default_language == "ta") ? 'selected="selected"' : ''; ?> >Tamil</option>
                        <option value="te" <?php echo ($default_language == "te") ? 'selected="selected"' : ''; ?> >Telugu</option>
                        <option value="th" <?php echo ($default_language == "th") ? 'selected="selected"' : ''; ?> >Thai</option>
                        <option value="tr" <?php echo ($default_language == "tr") ? 'selected="selected"' : ''; ?> >Turkish</option>
                        <option value="uk" <?php echo ($default_language == "uk") ? 'selected="selected"' : ''; ?> >Ukrainian</option>
                        <option value="vi" <?php echo ($default_language == "vi") ? 'selected="selected"' : ''; ?> >Vietnamese</option>
                        <option value="zh-CN" <?php echo ($default_language == "zh-CN") ? 'selected="selected"' : ''; ?> >Chinese (Simplified)</option>
                        <option value="zh-TW" <?php echo ($default_language == "zh-TW") ? 'selected="selected"' : ''; ?> >Chinese (Traditional)</option>
                    </select> <small>Default language for directions</small>
                </td>
            </tr>
            <tr>
                <td><label for="map_width">Map Width: </label></td>
                <td><input type="text" name="map_width" size="30" value="<?php echo $default_map_width; ?>" /> <small>Default google map width</small></td>
            </tr>
            <tr>
                <td><label for="map_height">Map Height: </label></td>
                <td><input type="text" name="map_height" size="30" value="<?php echo $default_map_height; ?>" /> <small>Default google map height</small></td>
            </tr>
            <tr>
                <td></td>
                <td><input class="button-primary" type="submit" name="submit" value="Update Settings"/></td>
            </tr>
            </tbody>
        </table>
    </form>
    <?php
}

function add_menu_item() {
    add_plugins_page('MK-GDC Options Page', 'MK-GDC Options', 8, __FILE__, 'mk_options_page');
}

add_action('admin_menu', 'add_menu_item');
?>