<?php
include 'util/main.php';
require('model/ip.php');
require('model/accuweather.php');


$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'search';
    }
}
//$ip = get_client_ip();
//$loc_key = get_current_location_id($ip);
//$weather_data = get_12hour_forcast($loc_key['key']);

include('default.php');
?>
