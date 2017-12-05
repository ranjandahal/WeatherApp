<?php
/**
 * Created by PhpStorm.
 * User: WeatherMate Team
 * Date: 11/23/17
 * Time: 6:24 PM
 */
require_once('constants.php');

//$darksky_data_arr = darksky_forecast($lat_long);

function darksky_forecast($lat_long){
    $latitude = $lat_long['lat'];
    $longitude = $lat_long['lon'];

    $url = DARK_SKY_BASE_URL . DARK_SKY_KEY. '/' . $latitude . ',' . $longitude;
    $url_get_data = file_get_contents($url);
    $json_obj_darksky = json_decode($url_get_data,true);
    $loop_count = 0;
    if(!is_null($json_obj_darksky)){
    //Hourly Data
        foreach ($json_obj_darksky['hourly']['data'] as $key => $result){
            $hour = date('m.d.Y H:i:s',$result['time']);
            $icon = $result['icon'];
            $icon_phrase = $result['summary'];
            $temp = $result['temperature'];
            $real_feel_temp = $result['apparentTemperature'];
            $wind_speed = $result['windSpeed'];
            $humidity = $result['humidity'];
            $rain_probability = $result['precipIntensity'];
            if(isset($result['precipAccumulation'])){
                $snow_probability = $result['precipAccumulation'];
            }else{
                $snow_probability = null;
            }
            $cloud_cover = $result['cloudCover'];
            $prep_probability = $result['precipProbability'];
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
            $loop_count++;
            if($loop_count == 12){break;}
        }
        return $hour12_object;
    }
    return false;
}

/*
 * For debugging purposes only*/
// Uncomment below to check DarkSky Data Call
//$lat_long = array("lat"=>42.3601, "lon"=>-71.0589);
//$weather_data_darksky = darksky_forecast($lat_long);
//print"<pre>";
//print_r($weather_data_darksky);
//print "</pre>";
?>

