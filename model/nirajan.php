<?php
/**
 * Created by PhpStorm.
 * User: Nirajan
 * Date: 11/28/2017
 * Time: 11:55 PM
 */

//require_once('constants.php');
//


ipAddressData('158.121.249.22');

function ipAddressData($search)
{
    $getZipCode = get_current_location_id("{$search}");
    echo "Nirajan". $getZipCode;
    currentHourData($getZipCode);
    sixHourData($getZipCode);
    twelveHourData($getZipCode);
    twentyFourHourData($getZipCode);
}

function currentHourData($search)
{
    $URL =get_current_URL($search);
    getCurrentTemperature("{$URL}");
}


function sixHourData($search)
{
    $URL =get_weekly_URL($search);
    getSixHourTemperature("{$URL}");

}

function twelveHourData($search)
{
    $URL =get_weekly_URL($search);
    getTwelveHourTemperature("{$URL}");
}

function twentyFourHourData($search)
{
    $URL =get_weekly_URL($search);
    getTwentyFourHourTemperature("{$URL}");
}

//return array Key which has location information for ip address
function get_current_location_id($ip){
    $url = LOCATION_IP_URL . ACCU_WEATHER_KEY;
    $query = '&q=' . $ip;

    $results = file_get_contents($url . $query);
    $results = json_decode($results, true);
    echo $results;
    if(!is_null($results)) {
        $local_name = $results['LocalizedName'];
        $key = $results['Key'];
        $zip = $results['PrimaryPostalCode'];
        $state = $results['AdministrativeArea']['ID'];
        $geo = array('lat' => $results['GeoPosition']['Latitude'],
            'lon' => $results['GeoPosition']['Longitude']);

        $ip_object = array('localname' => $local_name,
            'key' => $key,
            'postal' => $zip,
            'state' => $state,
            'geo' => $geo
        );

        return $zip;
        //return $ip_object;
    }
    return false;
}


/**
 * @param $string
 * returns url of the current weather of user's string
 */
function get_current_URL($zipCode_Or_CityName)
{
    $firstCharacter= $zipCode_Or_CityName[0];
    /**
     * if $string[0] is digit then return zip code url else return city name url
     */
    if($firstCharacter=="0" || $firstCharacter=="1" || $firstCharacter=="2"
        || $firstCharacter=="3"|| $firstCharacter=="4"|| $firstCharacter=="5"
        || $firstCharacter=="6"|| $firstCharacter=="7"|| $firstCharacter=="8"
        || $firstCharacter=="9")
    {
        $URL = "https://api.openweathermap.org/data/2.5/weather?zip=";
        $ZIP = rawurlencode("$zipCode_Or_CityName");
        $openMapWatherApiKey = "49d81087680614c83a1c4ee91a328384";
        $URL_LINK = "{$URL}"."{$ZIP}"."&APPID={$openMapWatherApiKey}";
        //getCurrentTemperature("{$URL_LINK}");
        return $URL_LINK;

    }
    else
    {
        $URL = "https://api.openweathermap.org/data/2.5/weather?q=";
        $address = rawurlencode("$zipCode_Or_CityName");
        $openMapWatherApiKey = "49d81087680614c83a1c4ee91a328384";
        $URL_LINK = "{$URL}"."{$address}"."&APPID={$openMapWatherApiKey}";
        //getCurrentTemperature($URL_LINK);
        return $URL_LINK;
    }
}


//returns URL for weekly Weather for cities
function get_weekly_URL($zipCode_Or_CityName){
    $firstCharacter= $zipCode_Or_CityName[0];
    /**
     * if $zipCode_Or_CityName[0] is digit then return zip code url else return city name url
     */
    if($firstCharacter=="0" || $firstCharacter=="1" || $firstCharacter=="2"
        || $firstCharacter=="3"|| $firstCharacter=="4"|| $firstCharacter=="5"
        || $firstCharacter=="6"|| $firstCharacter=="7"|| $firstCharacter=="8"
        || $firstCharacter=="9")
    {
        $URL = "https://api.openweathermap.org/data/2.5/forecast?zip=";
        $ZIP = rawurlencode("$zipCode_Or_CityName");
        $openMapWatherApiKey = "49d81087680614c83a1c4ee91a328384";
        $URL_LINK = "{$URL}"."{$ZIP}"."&APPID={$openMapWatherApiKey}";
        //getSixHourTemperature("{$URL_LINK}");
        return $URL_LINK;
    }
    else
    {
        $URL = "http://api.openweathermap.org/data/2.5/forecast?q=";
        $address = rawurlencode("$zipCode_Or_CityName");
        $openMapWatherApiKey = "49d81087680614c83a1c4ee91a328384";
        $URL_LINK = "{$URL}"."{$address}"."&APPID={$openMapWatherApiKey}";
        //getSixHourTemperature("{$URL_LINK}");
        return $URL_LINK;
    }
}

/**
 * @param $urlApi
 * returns current temperature from the urlApi provided
 */
function getCurrentTemperature($urlApi){
    $str = file_get_contents("{$urlApi}");
    $jason = json_decode($str,true);
    $tempreature = $jason['main']['temp'];
    $windSpeed = $jason['wind']['speed'];
    $pressure = $jason['main']['pressure'];
    $icon = $jason['weather']['0']['icon'];
    $icon_phrase = $jason['weather']['0']['description'];


    $current_object = array(
        'icon' => $icon,
        'icon_phrase' => $icon_phrase,
        'icon_url' => "http://openweathermap.org/img/w/" . $icon . '.png',
        'temp' => $tempreature,
        'pressure' => $pressure,
        'windSpeed'=>$windSpeed,
    );




    return $current_object;
}


//returns temperature for six hours
function getSixHourTemperature($url){
    $str = file_get_contents("{$url}");
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




        $sixHour_object["$i"] = array(
            'dateAndTime'=>$oneDateAndTime,
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'icon_url' => "http://openweathermap.org/img/w/" . $icon . '.png',
            'temp' => $oneTemperature,
            'pressure' => $pressure,
            'windSpeed'=>$windSpeed,
        );


    }

    return $sixHour_object;
    echo "<br>";
}

//returns temperature for twelve hours
function getTwelveHourTemperature($url){
    $str = file_get_contents("{$url}");
    $jason = json_decode($str,true);
    echo "<br>";
    echo "Time Date and Temperture For 12 Hours:";
    $count = 12;
    for ($i=0;$i<$count;$i++)
    {
        echo "<br>";
        $oneDateAndTime = $jason['list']["{$i}"]['dt_txt'];
        $oneTemperature=$jason['list']["{$i}"]['main']['temp'];
        $windSpeed = $jason['list']["{$i}"]['wind']['speed'];
        $pressure = $jason['list']["{$i}"]['main']['pressure'];
        $icon = $jason['list']["{$i}"]['weather']['0']['icon'];
        $icon_phrase = $jason['list']["{$i}"]['weather']['0']['description'];



        $twelveHour_object[] = array(
            'dateAndTime'=>$oneDateAndTime,
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'icon_url' => "http://openweathermap.org/img/w/" . $icon . '.png',
            'temp' => $oneTemperature,
            'pressure' => $pressure,
            'windSpeed'=>$windSpeed

            );




    }
    return $twelveHour_object;
    echo "<br>";
}
//returns temperature for twenty four hours
function getTwentyFourHourTemperature($url){
    $str = file_get_contents("{$url}");
    $jason = json_decode($str,true);
    echo "<br>";
    echo "Time Date and Temperture For 24 Hours:";
    $count = 24;
    for ($i=0;$i<$count;$i++)
    {
        echo "<br>";
        $oneDateAndTime = $jason['list']["{$i}"]['dt_txt'];
        $oneTemperature=$jason['list']["{$i}"]['main']['temp'];
        $windSpeed = $jason['list']["{$i}"]['wind']['speed'];
        $pressure = $jason['list']["{$i}"]['main']['pressure'];
        $icon = $jason['list']["{$i}"]['weather']['0']['icon'];
        $icon_phrase = $jason['list']["{$i}"]['weather']['0']['description'];


        $twelveHour_object[] = array(
            'dateAndTime'=>$oneDateAndTime,
            'icon' => $icon,
            'icon_phrase' => $icon_phrase,
            'icon_url' => "http://openweathermap.org/img/w/" . $icon . '.png',
            'temp' => $oneTemperature,
            'pressure' => $pressure,
            'windSpeed'=>$windSpeed,
        );


    }
    return $twelveHour_object;
    echo "<br>";
}

?>