<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 7:49 PM
 */
require('../model/accuweather.php');
require ('../model/nirajan.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'search';
    }
}

$search = filter_input(INPUT_GET, 'search');

$zip_key = get_zip_id($search);

$data = get_12hour_forcast($zip_key['key']);
print_r(json_encode($data));

//for weatherMAp

$daraForIpAddress = ipAddressData($search);
print_r(json_encode($daraForIpAddress));

$dataForCurrentWeatherMap =currentHourData($search);
print_r(json_encode($dataForCurrentWeatherMap));


$dataForSixHourWeatherMap = sixHourData($search);
print_r(json_encode($dataForSixHourWeatherMap));

$dataForTwelveHourWeatherMap = twelveHourData($search);
print_r(json_encode($dataForTwelveHourWeatherMap));

$dataForTwentyFourHourWeatherMap = twentyFourHourData($search);
print_r(json_encode($dataForTwentyFourHourWeatherMap));


?>