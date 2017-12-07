<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 7:49 PM
 */
require('../model/accuweather.php');
require_once('../model/darksky.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'search';
    }
}

$search = filter_input(INPUT_GET, 'search');

if(is_int($search) && strlen($search) == 5){
    $zip_key = get_zip_id($search);
}else{
    $zip_key = get_city_id($search);
}

//OpenWeather
$data[] = get_weather_info($zip_key['postal'], 12);

//DarkSky
$data[] = darksky_forecast_hourly($zip_key['geo'], 12);

//Accuweather data pull
$data[] = get_12hour_forcast($zip_key['key']);

//NOOA
$data[] = get_12hour_forcast($zip_key['key']);

print_r(json_encode($data));
?>