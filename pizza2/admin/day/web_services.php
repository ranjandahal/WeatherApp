<?php
// Functions to do the base web services needed
// Note that all needed web services are sent from this day directory
// The functions here should throw up to their callers, just like
// the functions in model.
//
// Post day number to server
// Returns if successful, or throws if not
function post_product($httpClient, $base_url, $product) {
    error_log('post_day to server: ' . $product);
    $url = $base_url . '/products/';
    $response = $httpClient->request('POST', $url, ['json' => $product]);
    return $response->getBody();
}

function get_product($httpClient, $base_url, $product_id) {
    error_log('post_day to server: ' . $product_id);
    $url = $base_url . '/products/' . $product_id;
    $response = $httpClient->request('GET', $url);
    return $response->getBody();
}

function post_day($httpClient, $base_url, $day) {
    error_log('post_day to server: ' . $day);
    $url = $base_url . '/day/';
    $response = $httpClient->request('POST', $url, ['json' => $day]);
    return $response->getBody();
}

function get_day($httpClient, $base_url, $order_id) {
    error_log('post_day to server: ' . $day);
    $url = $base_url . '/day/' . $day;
    $response = $httpClient->request('GET', $url);
    return $response->getBody();
}

// TODO: POST order and get back location (i.e., get new id), get all orders 
// in server and/or get a specific order by orderid

function post_order($httpClient, $base_url, $order){
    error_log('post_order to server: ' . $order['customerID']);
    $url = $base_url . '/orders/';
    $response = $httpClient->request('POST', $url, ['json' => $order]);
    
    //Retursn URI with order id
    return $response->getHeader('Location');
}

function get_order($httpClient, $base_url, $order_id){
    error_log('get_order to server: ' . $order_id);
    $url = $base_url . '/orders/' . $order_id;
    $response = $httpClient->request('GET', $url);
    return json_decode($response->getBody()->getContents(), TRUE);
}

function get_orders($httpClient, $base_url){
    error_log('get_orders to server: This returns all orders');
    $url = $base_url . '/orders/';
    $response = $httpClient->request('GET', $url);
    return json_decode($response->getBody()->getContents(), TRUE);
}
