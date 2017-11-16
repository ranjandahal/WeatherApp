<?php

// This function calculates a shipping charge of $5 per item
// but it only charges shipping for the first 5 items
function shipping_cost() {
    $item_count = cart_item_count();
    $item_shipping = 5;   // $5 per item
    if ($item_count > 5) {
        $shipping_cost = $item_shipping * 5;
    } else {
        $shipping_cost = $item_shipping * $item_count;
    }
    return $shipping_cost;
}

// This function calcualtes the sales tax,
// but only for orders in California (CA)
function tax_amount($subtotal) {
    $shipping_address = get_address($_SESSION['user']['shipAddressID']);
    $state = $shipping_address['state'];
    $state = strtoupper($state);
    switch ($state) {
        case 'CA': $tax_rate = 0.09; break;
        default: $tax_rate = 0; break;
    }
    return round($subtotal * $tax_rate, 2);
}

function card_name($card_type) {
    switch($card_type){
        case 1: 
           return 'MasterCard';
        case 2: 
            return 'Visa';
        case 3: 
            return 'Discover';
        case 4:
            return 'American Express';
        default:
            return 'Unknown Card Type';
    }
}
// renamed for project 2 to make clear it uses notion of current user
// that does not apply in the web service case
// For project 2: note add_order at end of file that doesn't use a current user
function add_order_for_current_user($card_type, $card_number, $card_cvv, $card_expires) {
    global $db;
    $customer_id = $_SESSION['user']['customerID'];
    $billing_id = $_SESSION['user']['billingAddressID'];
    $shipping_id = $_SESSION['user']['shipAddressID'];
    $shipping_cost = shipping_cost();
    $tax = tax_amount(cart_subtotal());
    $order_date = date("Y-m-d H:i:s");

    $query = '
         INSERT INTO orders (customerID, orderDate, shipAmount, taxAmount,
                             shipAddressID, cardType, cardNumber,
                             cardExpires, billingAddressID)
         VALUES (:customer_id, :order_date, :ship_amount, :tax_amount,
                 :shipping_id, :card_type, :card_number,
                 :card_expires, :billing_id)';
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->bindValue(':order_date', $order_date);
    $statement->bindValue(':ship_amount', $shipping_cost);
    $statement->bindValue(':tax_amount', $tax);
    $statement->bindValue(':shipping_id', $shipping_id);
    $statement->bindValue(':card_type', $card_type);
    $statement->bindValue(':card_number', $card_number);
    $statement->bindValue(':card_expires', $card_expires);
    $statement->bindValue(':billing_id', $billing_id);
    $statement->execute();
    $order_id = $db->lastInsertId();
    $statement->closeCursor();
    return $order_id;
}

function add_order_item($order_id, $product_id,
                        $item_price, $discount, $quantity) {
    global $db;
    $query = '
        INSERT INTO OrderItems (orderID, productID, itemPrice,
                                discountAmount, quantity)
        VALUES (:order_id, :product_id, :item_price, :discount, :quantity)';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $order_id);
    $statement->bindValue(':product_id', $product_id);
    $statement->bindValue(':item_price', $item_price);
    $statement->bindValue(':discount', $discount);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor();
}

function get_order($order_id) {
    global $db;
    //Modified select query to get status of delivery for current order
    $query = "SELECT *, IF(DATEDIFF(NOW(), shipDate) >= deliveryDay, 'true','false') as 'delivered' 
                FROM orders WHERE orderID = :order_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $order_id);
    $statement->execute();
    $order = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $order;   
}

function get_minimal_order_data($order_id) {
    global $db;
    //Modified select query to get status of delivery for orders
    $query = "SELECT customerID, orderID,"
            . " IF(DATEDIFF(NOW(), shipDate) >= deliveryDay, 'true','false') as 'delivered' "
            . "FROM orders WHERE orderID = :order_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $order_id);
    $statement->execute();
    $order = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    
    foreach($order as $ord){
        $order_items = get_order_items($ord['orderID']);
        if(count($order_items) > 0){
            $item1 = array('productID'=>$order_items[0]['productID'], 'quantity'=>$order_items[0]['quantity']);
            $item2 = array('productID'=>$order_items[1]['productID'], 'quantity'=>$order_items[1]['quantity']);
            $order['items'] = array($item1, $item2);
        }else{
            $ord['items'] = array();  //null items if none
        }
    }
    return $order;
}

function get_orders() {
    global $db;
    //Modified select query to get status of delivery for orders
    $query = "SELECT * FROM orders";
    $statement = $db->prepare($query);
    $statement->execute();
    $order = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $order;
}

function get_minimal_orders_data() {
    global $db;
    //Modified select query to get status of delivery for orders
    $query = "SELECT customerID, orderID,"
            . " IF(DATEDIFF(NOW(), shipDate) >= deliveryDay, 'true','false') as 'delivered' FROM orders";
    $statement = $db->prepare($query);
    $statement->execute();
    $order = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    $orders = array();
    foreach($order as $ord){
        $order_items = get_order_items($ord['orderID']);
        $items = [];
        if(count($order_items) > 0){
            $item1 = array('productID'=>$order_items[0]['productID'], 'quantity'=>$order_items[0]['quantity']);
            $item2 = array('productID'=>$order_items[1]['productID'], 'quantity'=>$order_items[1]['quantity']);
            $items[] = array($item1, $item2);
            $orders[] = array('customerID'=>$ord['customerID'],'orderID'=>$ord['orderID'],'items'=> $items);
        }
    }
    return $orders;
}

function get_order_items($order_id) {
    global $db;
    $query = 'SELECT * FROM OrderItems WHERE orderID = :order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $order_id);
    $statement->execute();
    $order_items = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $order_items;
}

function get_orders_by_customer_id($customer_id) {
    global $db;
    $query = 'SELECT * FROM orders WHERE customerID = :customer_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->execute();
    $order = $statement->fetchAll();
    $statement->closeCursor();
    return $order;
}

function get_unfilled_orders() {
    global $db;
    $query = 'SELECT * FROM orders
              INNER JOIN customers
              ON customers.customerID = orders.customerID
              WHERE shipDate IS NULL ORDER BY orderDate';
    $statement = $db->prepare($query);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;
}

function get_filled_orders() {
    global $db;
    $query =
        'SELECT * FROM orders
         INNER JOIN customers
         ON customers.customerID = orders.customerID
         WHERE shipDate IS NOT NULL ORDER BY orderDate';
    $statement = $db->prepare($query);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;
}

function set_ship_date($order_id) {
    global $db;
    $ship_date = date("Y-m-d H:i:s");
    $query = '
         UPDATE orders
         SET shipDate = :ship_date
         WHERE orderID = :order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':ship_date', $ship_date);
    $statement->bindValue(':order_id', $order_id);
    $statement->execute();
    $statement->closeCursor();
}

function delete_order($order_id) {
    global $db;
    $query = 'DELETE FROM orders WHERE orderID = :order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $order_id);
    $statement->execute();
    $statement->closeCursor();
}

// added for project 2: add_order that doesn't need a "current user"
// and only uses some columns
// Note that add_order_item above can be used for adding order items
function add_order($customerID, $order_date, $deliveryDay)
{
    global $db;
    $shipAmount = 5.00;
    $taxAmount = 0.00;
    $shipAddressID = 7;
    $cardType = 2;
    $cardNumber = '4111111111111111';
    $cardExpires = '08/2016';
    $billingAddressID = 7;

    $query = 'INSERT INTO ORDERS (customerID, orderDate, shipAmount, taxAmount,
                             shipAddressID, cardType, cardNumber,
                             cardExpires, billingAddressID, deliveryDay)
         VALUES (:customer_id, :order_date, :ship_amount, :tax_amount,
                 :shipping_id, :card_type, :card_number,
                 :card_expires, :billing_id, :deliveryDay)';
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customerID);
    $statement->bindValue(':order_date', $order_date);
    $statement->bindValue(':ship_amount', $shipAmount);
    $statement->bindValue(':tax_amount', $taxAmount);
    $statement->bindValue(':shipping_id', $shipAddressID);
    $statement->bindValue(':card_type', $cardType);
    $statement->bindValue(':card_number', $cardNumber);
    $statement->bindValue(':card_expires', $cardExpires);
    $statement->bindValue(':billing_id', $billingAddressID);
    $statement->bindValue(':deliveryDay', $deliveryDay);
    $statement->execute();
    $order_id = $db->lastInsertId();
    $statement->closeCursor();
    return $order_id;
}

function reinitialize_orders()
{
    global $db;
    $query='truncate table orders;';
    $query.='truncate table orderItems;';
    $statement = $db->prepare($query);
    $statement->execute(); 
    $statement->closeCursor();
    reset_auto_increment();
}

// Note that this doesn't work properly on mysql before v. 5.6
// In such cases, drop and recreate the table
// or live with higher orderId numbers on reruns
function reset_auto_increment()
{
    global $db;
    $query='ALTER TABLE orders AUTO_INCREMENT = 0;';
    $statement = $db->prepare($query);
    $statement->execute(); 
    $statement->closeCursor();
}

?>