<?php
/**
 * Created by PhpStorm.
 * User: Nirajan
 * Date: 11/28/2017
 * Time: 8:38 PM
 */

require_once('constants.php');

$googleAPTKeyForMe = "AIzaSyD_5iXKh323S9bVK5NP1ZpwKjNzoxVPIW8";

getAddressNameFromZipCode("02134");
//addressNameFromZipCode
function getAddressNameFromZipCode($zip)
{
    $url = weatherMAp_CurrentBaseUrl_Zip.$zip.weatherMapApiKey;
    echo $url;
    echo "<br>";

    $results = file_get_contents("{$url}");
    $results = json_decode($results, true);



}

get_1hour_forcast("02134");

function get_1hour_forcast($zip){

    $url = weatherMAp_CurrentBaseUrl_Zip.$zip.weatherMapApiKey;
    //echo $url;
   // echo "<br>";

    $results = file_get_contents("{$url}");
    $results = json_decode($results, true);
    $temperature = $results['main']['temp'];
   // echo $results;

    if(!is_null($results)) {
        foreach ($results as $result) {
            $hour = date("HH", substr($result['EpochDateTime'], 0, 10));
            $icon = $result['WeatherIcon'];
            $icon_phrase = $result['IconPhrase'];
            $temp = $result['Temperature']['Value'];
            $prep = $result['PrecipitationProbability'];
        }
        $hour_object = array('hour' => $hour,
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'icon_url' => '/icons/' . $icon . '-s.png',
            'temp' => $temp,
            'prep' => $prep,
        );
        return $hour_object;
    }
    return false;
}
