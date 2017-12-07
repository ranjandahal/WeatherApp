<?php
/**
 * Created by PhpStorm.
 * User: Nirajan
 * Date: 11/28/2017
 * Time: 11:55 PM
 */


require_once('constants.php');

//returns zip code which has location information for ip address
function getZip_From_IpLocation($ipLocation){

    $apiKey = "?apikey=JAVF2lEdSJllL7h1ZbeOoVQqj5hABP5p";
    $url = "http://dataservice.accuweather.com/locations/v1/cities/ipaddress"."?apikey=JAVF2lEdSJllL7h1ZbeOoVQqj5hABP5p"."&q="."{$ipLocation}";
    $results = file_get_contents("{$url}");
    $results = json_decode($results, true);
    $zip = $results['PrimaryPostalCode'];
    return $zip;

}

function get_weather_info($zip, $hour){
    $url_for_Current = OPEN_WEATHER_MAP_BASE_URL_CURRENT . $zip . OPEN_WEATHER_MAP_KEY;
    $url_for_weekly =  OPEN_WEATHER_MAP_BASE_URL_WEEKLY .  $zip . OPEN_WEATHER_MAP_KEY;


    if($hour=="1"||$hour=="Current"||$hour=="current"||$hour=="CURRENT")
    {
        $str = file_get_contents("{$url_for_Current}");
        $jason = json_decode($str,true);
        $tempreature = $jason['main']['temp'];
        $windSpeed = $jason['wind']['speed'];
        $pressure = $jason['main']['pressure'];
        $icon = $jason['weather']['0']['icon'];
        $icon_phrase = $jason['weather']['0']['description'];
        $humidity = $jason['main']['humidity'];


        $current_object = array(
            'hour' => "N/A",
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'temp' => $tempreature,
            'real_feel_temp' => "N/A",
            'wind_speed' => $windSpeed,
            'humidity' => $humidity,
            'rain_probability' => "N/A",
            'snow_probability' => "N/A",
            'cloud_cover' => "N/A",
            'prep' => "N/A"
        );


        echo $tempreature;

        return $current_object;

    }

    else if($hour =="6"||$hour=="six"||$hour=="Six"||$hour=="SIX")
    {
        $str = file_get_contents("{$url_for_weekly}");
        $jason = json_decode($str,true);
        echo "<br>";
        echo "Time Date and Temperture For 6 Hours:";
        $count = 6;
        for ($i=0;$i<$count;$i++)
        {
            echo "<br>";
            $oneDateAndTime = $jason['list']["{$i}"]['dt_txt'];
            $oneTemperature=$jason['list']["{$i}"]['main']['temp'];
            $windSpeed = $jason['list']["{$i}"]['wind']['speed'];
            $pressure = $jason['list']["{$i}"]['main']['pressure'];
            $icon = $jason['list']["{$i}"]['weather']['0']['icon'];
            $icon_phrase = $jason['list']["{$i}"]['weather']['0']['description'];
            $humidity = $jason['list']["{$i}"]['main']['humidity'];

            echo $oneTemperature;
            echo "<br>";


            $sixHour_object[] = array(
                'hour' => $oneDateAndTime,
                'icon' => $icon,
                'icon_phrase' => $icon_phrase,
                'temp' => $oneTemperature,
                'real_feel_temp' => "N/A",
                'wind_speed' => $windSpeed,
                'humidity' => $humidity,
                'rain_probability' => "N/A",
                'snow_probability' => "N/A",
                'cloud_cover' => "N/A",
                'prep' => "N/A"

            );


        }

        return $sixHour_object;
        echo "<br>";
    }

    else if($hour =="12"||$hour=="twelve"||$hour=="Twelve"||$hour=="TWELVE")
    {
        $str = file_get_contents("{$url_for_weekly}");
        $jason = json_decode($str,true);
        echo "<br>";
        echo "Time Date and Temperture For 6 Hours:";
        $count = 12;
        for ($i=0;$i<$count;$i++) {
            echo "<br>";
            $oneDateAndTime = $jason['list']["{$i}"]['dt_txt'];
            $oneTemperature = $jason['list']["{$i}"]['main']['temp'];
            $windSpeed = $jason['list']["{$i}"]['wind']['speed'];
            $pressure = $jason['list']["{$i}"]['main']['pressure'];
            $icon = $jason['list']["{$i}"]['weather']['0']['icon'];
            $icon_phrase = $jason['list']["{$i}"]['weather']['0']['description'];
            $humidity = $jason['list']["{$i}"]['main']['humidity'];


            $twelveHour_object[] = array(
                'hour' => $oneDateAndTime,
                'icon' => $icon,
                'icon_phrase' => $icon_phrase,
                'temp' => $oneTemperature,
                'real_feel_temp' => "N/A",
                'wind_speed' => $windSpeed,
                'humidity' => $humidity,
                'rain_probability' => "N/A",
                'snow_probability' => "N/A",
                'cloud_cover' => "N/A",
                'prep' => "N/A"
            );
        }

            return $twelveHour_object;
    }
    else
    {
        $str = file_get_contents("{$url_for_weekly}");
        $jason = json_decode($str,true);
        echo "<br>";
        echo "Time Date and Temperture For 6 Hours:";
        $count = 24;
        for ($i=0;$i<$count;$i++) {
            echo "<br>";
            $oneDateAndTime = $jason['list']["{$i}"]['dt_txt'];
            $oneTemperature = $jason['list']["{$i}"]['main']['temp'];
            $windSpeed = $jason['list']["{$i}"]['wind']['speed'];
            $pressure = $jason['list']["{$i}"]['main']['pressure'];
            $icon = $jason['list']["{$i}"]['weather']['0']['icon'];
            $icon_phrase = $jason['list']["{$i}"]['weather']['0']['description'];
            $humidity = $jason['list']["{$i}"]['main']['humidity'];


            $twentyFourHour_object[] = array(
                'hour' => $oneDateAndTime,
                'icon' => $icon,
                'icon_phrase' => $icon_phrase,
                'temp' => $oneTemperature,
                'real_feel_temp' => "N/A",
                'wind_speed' => $windSpeed,
                'humidity' => $humidity,
                'rain_probability' => "N/A",
                'snow_probability' => "N/A",
                'cloud_cover' => "N/A",
                'prep' => "N/A"
            );
        }

        return $twentyFourHour_object;
    }
}

?>