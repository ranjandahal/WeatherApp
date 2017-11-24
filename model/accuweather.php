<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 6:24 PM
 */
require_once('model/constants.php');

function get_city_id($city){
    $url = LOCATION_CITY_URL . ACCU_WEATHER_KEY;
    $query = '&q' . $city;

    // create curl resource
    $ch = curl_init($url . $query);
    $results = curl_exec($ch);
    curl_close($ch);

    // Will dump a beauty json :3
    var_dump(json_decode($results, true));

    foreach($results as $result) {
        if (strcmp($result['Country']['ID'], 'US') == 0){
            $local_name = $result['LocalizedName'];
            $key = $result['Key'];
            $state = $result['AdministrativeArea']['ID'];
            $geo = array('lat' => $result['GeoPosition']['Latitude'],
                'lon' => $result['GeoPosition']['Longitude']);
            break;
        }
    }
    $city_object = array('localname' => $local_name,
                            'key'=> $key,
                            'postal' => 0,
                            'state' => $state,
                            'geo'=> $geo
                        );
    return $city_object;
}

function get_zip_id($zip){
    $url = LOCATION_ZIP_URL . ACCU_WEATHER_KEY;
    $query = '?q' . $zip;

    // create curl resource
    $ch = curl_init($url . $query);
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

function get_1hour_forcast($key){
    $url = FORECASE_1HOUR_URL . $key . ACCU_WEATHER_KEY;

    // create curl resource
    $ch = curl_init($url);
    $results = curl_exec($ch);
    curl_close($ch);

    // Will dump a beauty json :3
    var_dump(json_decode($results, true));

    foreach($results as $result) {
        $hour = date("HH", substr($result['EpochDateTime'], 0, 10));
        $icon = $result['WeatherIcon'];
        $icon_phrase = $result['IconPhrase'];
        $temp = $result['Temperature']['Value'];
        $prep = $result['PrecipitationProbability'];
    }
    $hour_object = array( 'hour' => $hour,
                          'icon' => $icon,
                          'icon_phrase' => $icon_phrase,
                          'temp' => $temp,
                          'prep' => $prep,
                        );
    return $hour_object;
}

function get_12hour_forcast($key){
    $url = FORECASE_12HOUR_URL . $key . ACCU_WEATHER_KEY;

    // create curl resource
    $ch = curl_init($url);
    $results = curl_exec($ch);
    curl_close($ch);

    // Will dump a beauty json :3
    var_dump(json_decode($results, true));

    foreach($results as $result) {
        $hour = date("HH", substr($result['EpochDateTime'], 0, 10));
        $icon = $result['WeatherIcon'];
        $icon_phrase = $result['IconPhrase'];
        $temp = $result['Temperature']['Value'];
        $prep = $result['PrecipitationProbability'];
        $hour12_object[] = array( 'hour' => $hour,
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'temp' => $temp,
            'prep' => $prep,
        );
    }
    return $hour12_object;
}

function get_daily_forcast($key){
    $url = FORECASE_DAILY_URL . $key . ACCU_WEATHER_KEY;

    // create curl resource
    $ch = curl_init($url);
    $results = curl_exec($ch);
    curl_close($ch);

    // Will dump a beauty json :3
    var_dump(json_decode($results, true));

    /*foreach($results as $result) {
        if (strcmp($result['Country']['ID'], 'US') == 0) {
            $local_name = $result['LocalizedName'];
            $key = $result['Key'];
            $state = $result['AdministrativeArea']['ID'];
            $geo = array('lat' => $result['GeoPosition']['Latitude'],
                'lon' => $result['GeoPosition']['Longitude']);
            break;
        }
    }*/
}