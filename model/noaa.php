<?php
/**
 * Created by PhpStorm.
 * User: Kushal Khanal
 * Date: 11/23/17
 * Time: 6:24 PM

 * Written By kushal Khanal
 * Uses google Api call from Bikesh Manandhar's Darksky, to get the lat, and lon
 * the data response is not standard thus, has multiple for loops to extract the
 * required field.
 * $size is required to know either weather data for 1 hour, 6 hour, or 12 hr is required
 * the raw data gives weather data for more than next 50 hrs. thus, required hours can be extracted
 * Still uncomplete for Ip implementation
w
 */


require_once('accuweather.php');
noaa_forecast('158.121.249.23', '6');
noaa_forecast('02149', '1');
noaa_forecast('everett, MA', 2);
function get_values($response, $size){


    $counter = 1;
    $pcounter = 1;
    $cCounter = 1;
    $index = 0;
    $arr = array($size);

    foreach ($response as $item){
        if($counter == 6)
            break;
        if($counter == 4){
            //print_r($x);
            foreach ($item as $periods){
                if($cCounter==5) {
                    foreach ($periods as $vals) {
                        $array =$periods[$index];
                        foreach ($array as $items){
                            if($pcounter== 6){
                                $temp =$array->temperature;
                                $icon_phrase = $array->shortForecast;
                                $time = $array->startTime;
                                $time = explode('T',$time);
                                $time = substr($time[1],0,5);
                                $icon =$array->icon;
                                $wind_speed = $array->windSpeed;

                                $hour_object = array(
                                    'hour'=> $time,
                                    'icon'=> $icon,
                                    'icon_phrase' => $icon_phrase,
                                    'temp' => $temp,
                                    'real_feel_temp'=> $temp,
                                    'wind_speed'=> $wind_speed,
                                    'humidity'=> 0,
                                    'rain_probability' => 0,
                                    'snow_probability' => 0,
                                    'cloud_cover' => 0,
                                    'prep' => 0,

                                );
                                //print_r($hour_object['temp']);
                                echo "\r\n";
                                $arr[$index]= $hour_object;

                                break;
                            }
                            $pcounter++;
                        }
                        $pcounter= 0;
                        $index++;
                        if($index==6) {
                            break;
                        }
                    }
                }
                $cCounter++;
            }
            break;
        }
       // echo 'Line: ' . $counter;
        $counter++;
        echo "\r\n";
    }
    return $arr;
}

function get_address_info($searchedCity){
    // GOOGLE API KEY for getting geolocation info
    $GOOGLE_API_KEY_s1 = "AIzaSyBhBuNbrzlIhOJjiqRTAYWPlx9x_sp4QFM";

    if (filter_var($searchedCity, FILTER_VALIDATE_IP)) {
        //echo("$searchedCity is a valid IP address");
        $datas = get_current_location_id($searchedCity);
        return $datas['geo'];

    } else {

        //Allowing_url_fopen to be enabled so that we can use file_get_contents.
        ini_set("allow_url_fopen", 1);
        $url_link = "https://maps.googleapis.com/maps/api/geocode/json?address=" . rawurlencode("{$searchedCity}") . "&key={$GOOGLE_API_KEY_s1}";
        $json_url = file_get_contents($url_link);
        $json_obj = json_decode($json_url, true);

        if ($json_obj['status'] == "OK") {
            return $json_obj['results'][0]['geometry']['location'];
        }else
            return "ERROR";
    }
}

function get_raw_data_noaa($loc_info_noaa){

    //Latitude and Longitude
    $latitude = $loc_info_noaa['lat'];
    $longitude = $loc_info_noaa['lng'];


    //Allowing_url_fopen to be enabled so that we can use file_get_contents.
    $ch = curl_init('https://api.weather.gov/points/'.$latitude.','.$longitude.'/forecast/hourly');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSLVERSION => 6,
        CURLOPT_USERAGENT => 'Firefox',
    ]);
    $response = curl_exec($ch);
    if($response === false)
        exit(curl_error($ch));

    $response = json_decode($response);
    return $response;


}


function noaa_forecast($searchedinput, $size){

    $location_info = get_address_info($searchedinput);
    $raw_data =  get_raw_data_noaa($location_info);
    $values = get_values($raw_data, $size);
    echo  $values[0]['temp'];
    echo $values[0]['hour'];
    return $values;
}
?>
