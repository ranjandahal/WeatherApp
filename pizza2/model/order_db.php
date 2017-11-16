<?php
function get_preparing_orders($db) {
    $query = 'SELECT * FROM pizza_orders where status=\'Preparing\'';
    $statement = $db->prepare($query);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;  
}
function get_baked_orders($db) {
    $query = 'SELECT * FROM pizza_orders where status=\'Baked\'';
    $statement = $db->prepare($query);
    $statement->execute(); 
    $orders = $statement->fetchAll();
    $statement->closeCursor();    
    return $orders;  
}
function get_preparing_orders_of_room($db, $room) {
    $query = 'SELECT * FROM pizza_orders where status=\'Preparing\' and room_number=:room';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor(); 
    $orders = add_toppings_to_orders($db, $orders);
    return $orders;    
}

function get_baked_orders_of_room($db, $room) {
    $query = 'SELECT * FROM pizza_orders where status=\'Baked\' and room_number=:room';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor(); 
    $orders1 = add_toppings_to_orders($db, $orders);     
    return $orders1;    
}
// helper to above two functions
function add_toppings_to_orders($db, $orders) {
      for ($i=0; $i<count($orders);$i++) {
        $toppings = get_orders_toppings($db, $orders[$i]['id']);
        $orders[$i]['toppings'] = $toppings; // add toppings to order 
    } 
    return $orders;
}
// helper to above function
function get_orders_toppings($db, $order_id) {
    $query = 'select topping from order_topping '
            . 'where order_id=:order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id',$order_id);
    $statement->execute();
    $toppings = $statement->fetchAll();
    $statement->closeCursor();
    return $toppings;
}
function change_to_baked($db, $id) {
    $query = 'UPDATE pizza_orders SET status=\'Baked\' WHERE status=\'Preparing\' and id=:id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $statement->closeCursor();     
}

function get_oldest_preparing_id($db) {
    $query = 'SELECT min(id) id FROM pizza_orders where status=\'Preparing\'';
    $statement = $db->prepare($query);
    $statement->execute();
    $id = $statement->fetch()['id'];
    $statement->closeCursor();
    return $id;     
}

function update_to_finished($db, $room) {
    $query = 'UPDATE pizza_orders SET status=3 WHERE status = \'Baked\' and room_number = :room';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->execute();
    $statement->closeCursor();        
}

function add_order($db, $room,$size,$current_day,$status, $topping_ids) {
    $query = 'INSERT INTO pizza_orders(room_number, size, day, status) '
            . 'VALUES (:room,(select size_name from sizes where id = :size),:current_day,:status)';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->bindValue(':size',$size);
    $statement->bindValue(':current_day',$current_day);
    $statement->bindValue(':status',$status);
    $statement->execute();
    $statement->closeCursor(); 
    foreach ($topping_ids as $t) {
        add_order_topping($db, $t);
    }
}
// helper to add_order: uses last_insert_id() to pick up auto_increment value
function add_order_topping($db, $topping_id) {
    $topping_name = get_topping_name($db, $topping_id);
    $query = 'INSERT INTO order_topping(order_id, topping) '
            . 'VALUES (last_insert_id(),:topping_name)';
    $statement = $db->prepare($query);
    $statement->bindValue(':topping_name', $topping_name);
    $statement->execute();
    $statement->closeCursor();
}

/* This code was brought here to test webservice locally----------------
function get_product($product_id) {
    global $db;
    $query = '
        SELECT *
        FROM products p
           INNER JOIN categories c
           ON p.categoryID = c.categoryID
       WHERE productID = :product_id';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();
        //  eoneil: suppress unneeded numeric entries [1]=>... array elements in result
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        display_db_error($error_message);
    }
}

//Added this to place order
function get_product_id($product_code) {
    global $db;
    $query = '
        SELECT productID
        FROM products p
           INNER JOIN categories c
           ON p.categoryID = c.categoryID
       WHERE productCode = :product_Code';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':product_Code', $product_code);
        $statement->execute();
        //  eoneil: suppress unneeded numeric entries [1]=>... array elements in result
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        display_db_error($error_message);
    }
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
function add_orders($customerID, $order_date, $deliveryDay)
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
----------------*/