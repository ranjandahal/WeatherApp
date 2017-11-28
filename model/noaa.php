<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 6:24 PM
 */



$ch = curl_init('https://api.weather.gov/points/42.3605,-71.0596/forecast/hourly');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSLVERSION => 6,
    CURLOPT_USERAGENT => 'Firefox',
]);
$response = curl_exec($ch);
if($response === false)
    exit(curl_error($ch));
//print_r(json_decode($response));

$response = json_decode($response);

$counter = 1;
$pcounter = 1;
$cCounter = 1;

foreach ($response as $item){
    if($counter == 20)
        break;
    if($counter == 4){
            //print_r($x);
            foreach ($item as $periods){
                if($cCounter==5) {
                    //print_r($periods);
                    $array = $periods[0];
                    foreach ($array as $items){
                        if($pcounter== 6){
                            $temp =$array->temperature;
                            $forecast = $array->shortForecast;
                            $isdaytime= $array->isDaytime;
                            $wind = $array->windSpeed;

                        }
                       $pcounter++;
                    }
                    break;
                }
                $cCounter++;
            }
            break;
    }
    echo 'Line: ' . $counter;
    //print_r($item);
    $counter++;
    echo "\r\n";
    //break;
}
print_r("this is the temperature ".$temp);
print_r(" this is the forcast ".$forecast);
print_r(" this is the isdaytime ".$isdaytime);
print_r("this is the wind ".$wind);
echo "<pre>";
print_r($response);
echo "<pre>";

//print_r($response);


//foreach ($response as $item){
//    if($counter == 20)
//        break;
//    if($counter == 4){
//        //print_r($x);
//        foreach ($item as $periods){
//            if($cCounter==5) {
//                //print_r($periods);
//                $array = $periods[0];
//                foreach ($array as $items){
//                    if($pcounter== 6){
//                        print_r($array->temperature);
//                    }
//                    $pcounter++;
//                }
//                break;
//            }
//            $cCounter++;
//        }
//        break;
//    }