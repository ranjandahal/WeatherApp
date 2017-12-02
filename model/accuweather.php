<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 6:24 PM
 */
require_once('constants.php');

function get_current_location_id($ip){
    $url = LOCATION_IP_URL . ACCU_WEATHER_KEY;
    $query = '&q=' . $ip;

    $results = file_get_contents($url . $query);
    $results = json_decode($results, true);

    if(!is_null($results)) {
        $local_name = $results[0]['LocalizedName'];
        $key = $results[0]['Key'];
        $zip = $results[0]['PrimaryPostalCode'];
        $state = $results[0]['AdministrativeArea']['ID'];
        $geo = array('lat' => $results[0]['GeoPosition']['Latitude'],
            'lon' => $results[0]['GeoPosition']['Longitude']);

        $ip_object = array(
            'localname' => $local_name,
            'key' => $key,
            'postal' => $zip,
            'state' => $state,
            'geo' => $geo
        );
        return $ip_object;
    }
    return false;
}

function get_city_id($city){
    $url = LOCATION_CITY_URL . ACCU_WEATHER_KEY;
    $query = '&q=' . $city;

    $results = file_get_contents($url . $query);
    $results = json_decode($results, true);

    if(!is_null($results)) {
        foreach ($results as $result) {
            if (strcmp($result['Country']['ID'], 'US') == 0) {
                $local_name = $result['LocalizedName'];
                $key = $result['Key'];
                $state = $result['AdministrativeArea']['ID'];
                $geo = array('lat' => $result['GeoPosition']['Latitude'],
                    'lon' => $result['GeoPosition']['Longitude']);
                break;
            }
        }
        $city_object = array(
            'localname' => $local_name,
            'key' => $key,
            'postal' => 0,
            'state' => $state,
            'geo' => $geo
        );
        return $city_object;
    }
    return false;
}

function get_zip_id($zip){
    $url = LOCATION_ZIP_URL . ACCU_WEATHER_KEY;
    $query = '&q=' . $zip;

    $results = file_get_contents($url . $query);
    $results = json_decode($results, true);

    if(!is_null($results)) {
        $local_name = $results[0]['LocalizedName'];
        $key = $results[0]['Key'];
        $state = $results[0]['AdministrativeArea']['ID'];
        $geo = array('lat' => $results[0]['GeoPosition']['Latitude'],
            'lon' => $results[0]['GeoPosition']['Longitude']);

        $zip_object = array('localname' => $local_name,
            'key' => $key,
            'zip' => $zip,
            'state' => $state,
            'geo' => $geo
        );

        return $zip_object;
    }
    return false;
}

function get_1hour_forcast($key){
    $url = FORECASE_1HOUR_URL . $key . ACCU_WEATHER_KEY . '&details=true';

    $results = file_get_contents($url);
    $results = json_decode($results, true);

    if(!is_null($results)) {
        foreach ($results as $result) {
            $hour = date("HH", substr($result['EpochDateTime'], 0, 10));
            $icon = $result['WeatherIcon'];
            $icon_phrase = $result['IconPhrase'];
            $temp = $result['Temperature']['Value'];
            $real_feel_temp = $result['RealFeelTemperature']['Value'];
            $wind_speed = $result['Wind']['Speed']['Value'];
            $humidity = $result['RelativeHumidity'];
            $rain_probability = $result['RainProbability'];
            $snow_probability = $result['SnowProbability'];
            $cloud_cover = $result['CloudCover'];
            $prep_probability = $result['PrecipitationProbability'];
        }
        $hour_object = array(
            'hour' => $hour,
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'temp' => $temp,
            'real_feel_temp' => $real_feel_temp,
            'wind_speed' => $wind_speed,
            'humidity' => $humidity,
            'rain_probability' => $rain_probability,
            'snow_probability' => $snow_probability,
            'cloud_cover' => $cloud_cover,
            'prep' => $prep_probability
        );
        return $hour_object;
    }
    return false;
}

function get_12hour_forcast($key){
    $url = FORECASE_12HOUR_URL . $key . ACCU_WEATHER_KEY . '&details=true';

    $results = file_get_contents($url);
    $results = json_decode($results, true);

    if(!is_null($results)) {
        foreach ($results as $result) {
            $hour = date("HH", substr($result['EpochDateTime'], 0, 10));
            $icon = $result['WeatherIcon'];
            $icon_phrase = $result['IconPhrase'];
            $temp = $result['Temperature']['Value'];
            $real_feel_temp = $result['RealFeelTemperature']['Value'];
            $wind_speed = $result['Wind']['Speed']['Value'];
            $humidity = $result['RelativeHumidity'];
            $rain_probability = $result['RainProbability'];
            $snow_probability = $result['SnowProbability'];
            $cloud_cover = $result['CloudCover'];
            $prep_probability = $result['PrecipitationProbability'];
            $hour12_object[] = array(
                'hour' => $hour,
                'icon' => $icon,
                'icon_phrase' => $icon_phrase,
                'temp' => $temp,
                'real_feel_temp' => $real_feel_temp,
                'wind_speed' => $wind_speed,
                'humidity' => $humidity,
                'rain_probability' => $rain_probability,
                'snow_probability' => $snow_probability,
                'cloud_cover' => $cloud_cover,
                'prep' => $prep_probability
            );
        }
        return $hour12_object;
    }
    return false;
}

function get_daily_forcast($key){
    $url = FORECASE_DAILY_URL . $key . ACCU_WEATHER_KEY;

    $results = file_get_contents($url);
    $results = json_decode($results, true);

    return $results;
}