<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 6:24 PM
 */
require_once('model/constants.php');

function get_zip_weather($zip, $country){
    $url = OPEN_WEATHER_MAP_BASE_URL. 'zip=' . $zip . ',' . $country . ACCU_WEATHER_KEY;

    // create curl resource
    $ch = curl_init($url);
    $results = curl_exec($ch);
    curl_close($ch);

    // Will dump a beauty json :3
    var_dump(json_decode($results, true));

    foreach($results as $result) {
        if (strcmp($result['Country']['ID'], 'US') == 0) {
            $local_name = $result['LocalizedName'];
            $key = $result['Key'];
            $state = $result['AdministrativeArea']['ID'];
            $geo = array('lat' => $result['GeoPosition']['Latitude'],
                'lon' => $result['GeoPosition']['Longitude']);
            break;
        }
    }
    $zip_object = array('localname' => $local_name,
        'key'=> $key,
        'postal' => $zip,
        'state' => $state,
        'geo'=> $geo
    );
    return $zip_object;
}