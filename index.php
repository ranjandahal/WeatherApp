<?php
include 'util/main.php';
require('model/ip.php');
require('model/accuweather.php');
require ('model/darksky.php');
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'search';
    }
}
$ip = get_client_ip();
echo "IP = ".$ip;
$loc_key = get_current_location_id($ip);
echo "loc_key = ".$loc_key;
$weather_data = get_12hour_forcast($loc_key['key']);

//DarkSky Data Call
$lat_long = array("lat"=>42.3605, "lon"=>-71.0589);
$weather_data_darksky = darksky_forecast($lat_long);
include('default.php');
?>
