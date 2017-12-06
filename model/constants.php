<?php

define('ACCU_WEATHER_BASE_URL', 'http://dataservice.accuweather.com/');
define('ACCU_WEATHER_KEY', '?apikey=0W8rK2H9d8E4fhNPxnbSXZF49SCdLTOl');
define('LOCATION_CITY_URL', ACCU_WEATHER_BASE_URL . 'locations/v1/cities/US/search');
define('LOCATION_IP_URL', ACCU_WEATHER_BASE_URL . 'locations/v1/cities/ipaddress');
define('LOCATION_ZIP_URL', ACCU_WEATHER_BASE_URL . 'locations/v1/postalcodes/US/search');
define('FORECASE_1HOUR_URL', ACCU_WEATHER_BASE_URL . 'forecasts/v1/hourly/1hour/');
define('FORECASE_12HOUR_URL', ACCU_WEATHER_BASE_URL . 'forecasts/v1/hourly/12hour/');
define('FORECASE_DAILY_URL', ACCU_WEATHER_BASE_URL . 'forecasts/v1/daily/1day/');

define('OPEN_WEATHER_MAP_BASE_URL_CURRENT', 'https://api.openweathermap.org/data/2.5/weather?zip=');
define('OPEN_WEATHER_MAP_BASE_URL_WEEKLY','https://api.openweathermap.org/data/2.5/forecast?zip=');

define('OPEN_WEATHER_MAP_KEY','&appid=49d81087680614c83a1c4ee91a328384');
define('IP_TO_ZIP_BASE_url','http://dataservice.accuweather.com/locations/v1/cities/ipaddress');
define('IP_TO_ZIP_BASE','?apikey=JAVF2lEdSJllL7h1ZbeOoVQqj5hABP5p&q=');

//define('OPEN_WEATHER_ZIP_URL', OPEN_WEATHER_MAP_BASE_URL . 'zip=')

define('NOAA_BASE_URL','');
define('NOAA_KEY','WGtnTKqqyspurDVazfGftfialHmUHaeS');

define('DARK_SKY_BASE_URL','');
define('DARK_SKY_KEY','8e5f4349749ce069d55a6f33315dec92');

//define('','');
//define('','');
?>

