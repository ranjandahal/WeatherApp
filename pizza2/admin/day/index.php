<?php
require('../../util/main.php');
require '../../vendor/autoload.php';
require('../../model/constants.php');
require('../../model/database.php');
require('../../model/day_db.php');
require('../../model/inventory_db.php');
require('../../model/initial.php');
require('web_services.php');

// Note that you don't have to put all your code in this file.
// You can use another file day_helpers.php to hold helper functions
// and call them from here.

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list';
    }
}
$spot = strpos($app_path, 'pizza2');
$part = substr($app_path, 0, $spot);
$base_url = $_SERVER['SERVER_NAME'] . $part . 'proj2_server/rest';
$httpClient = new \GuzzleHttp\Client();
$current_day = get_current_day($db);

if ($action == 'list') {
    try {
        $todays_orders = get_todays_orders($db, $current_day);
        $current_supplies = get_current_inventory($db);
        $current_undelivered_orders = get_undelivered_orders($db);
    } catch (Exception $e) {
        include('../../errors/error.php');
        exit();
    }
    include('day_list.php');
} else if ($action == 'next_day') {
    try {
        finish_orders_for_day($db, $current_day);
        increment_day($db);
        post_day($httpClient, $base_url, $current_day + 1);
        
        $current_undelivered_orders = get_undelivered_orders($db);
        $total_flour_qty_undelivered = 0;
        $total_cheese_qty_undelivered = 0;
        
        //Update inventory after proceeding to next day.
        foreach($current_undelivered_orders as $undelivered_order){
            //get order related information for current undelivered order id
            $order_data = get_order($httpClient,$base_url,$undelivered_order['order_id']);
            if($order_data['delivered'] == 'true'){
                update_inventory($db, $undelivered_order['flour_qty'], $undelivered_order['cheese_qty']);     //Update inventory data after being delivered
                delete_undelivered_orders($db, $undelivered_order['order_id']);  //Delete order data after being processed
            }else{
                $total_flour_qty_undelivered += $undelivered_order['flour_qty'];
                $total_cheese_qty_undelivered += $undelivered_order['cheese_qty'];
            }
        }
        
        $current_supplies = get_current_inventory($db);
        $flour_qty_to_order = 0;
        $cheese_qty_to_order = 0;
        
        //Check required quantities for flour and cheese based on current and undelivered units
        //Make calculations for required units
        if(($current_supplies[0]['flour_qty'] + $total_flour_qty_undelivered) < DAILY_UNIT_QTY){
            //Get needed qty by taking difference between current supplies and undelivered orders
            $needed_qty_flour = (DAILY_UNIT_QTY - $current_supplies[0]['flour_qty'] - $total_flour_qty_undelivered)/FLOUR_UNIT_QTY;
            //get remainder to calculate how many qty is needed
            $needed_qty_remainder_flour = $needed_qty_flour%FLOUR_UNIT_QTY;
            //Get integer part only
            $needed_qty_flour = intval($needed_qty_flour);
            //always order 1 extra unit if remainder is not 0
            $flour_qty_to_order = ($needed_qty_remainder_flour == 0 && $needed_qty_flour != 0)?$needed_qty_flour:($needed_qty_flour + 1);
        }
        if (($current_supplies[0]['cheese_qty'] + $total_cheese_qty_undelivered) < DAILY_UNIT_QTY){
            //get remainder to calculate how many qty is needed
            $needed_qty = (DAILY_UNIT_QTY - $current_supplies[0]['cheese_qty'] - $total_cheese_qty_undelivered)/CHEESE_UNIT_QTY;
            //Get integer part only
            $needed_qty_remainder = $needed_qty%CHEESE_UNIT_QTY;
            //Get integer part only
            $needed_qty = intval($needed_qty);
            //always order 1 extra unit if remainder is not 0
            $cheese_qty_to_order = ($needed_qty_remainder == 0 && $needed_qty != 0)?$needed_qty:($needed_qty + 1);
        }
        //Check if any item needed to order then place order, otherwise do nothing
        if($flour_qty_to_order > 0 || $cheese_qty_to_order > 0 ){
            //Preparing order related data for customer id 1
            //I am changing the logic that is suggested in document
            $order = array('customerID' => 1, 
                        'order_items'=>array(//Sending product code rather than product id which client wouldn't know
                                             array('product_code'=>'flour', 'quantity'=>$flour_qty_to_order),
                                             array('product_code'=>'cheese', 'quantity'=>$cheese_qty_to_order)
                                            )
                            );
            //place order
            $order_id_header = post_order($httpClient, $base_url, $order);
            //parse order_id from location header URI.
            $order_id = substr($order_id_header[0], strrpos($order_id_header[0], '/')+1);
            
            if(ctype_digit($order_id)){
                //add entry to undelivered table
                add_undelivered_order($db, $order_id, $flour_qty_to_order*FLOUR_UNIT_QTY, 
                                        $cheese_qty_to_order*CHEESE_UNIT_QTY);
            }else{
                throw new Exception("Failed to post order to server. Invalid Order Id when parsing from order header URI");
            }
        }
        header("Location: .");
    } catch (Exception $e) {
        include('../../errors/error.php');
        exit();
    }
} else if ($action == 'initial_db') {
    try {
        initial_db($db);
        post_day($httpClient, $base_url, 0);
        header("Location: .");
    } catch (Exception $e) {
        include ('../../errors/error.php');
        exit();
    } 
}
?>