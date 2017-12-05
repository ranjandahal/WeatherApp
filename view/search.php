<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 7:49 PM
 */
require('../model/accuweather.php');
require_once('../model/darksky.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'search';
    }
}

$search = filter_input(INPUT_GET, 'search');

$zip_key = get_zip_id($search);

//Accuweather data pull
$data[] = get_12hour_forcast($zip_key['key']);
print_r(json_encode($data));

$data_darksky[] = debug_darksky($search);
echo "<script>console.log( 'Searching inside: " . $data_darksky . "' );</script>";
?>