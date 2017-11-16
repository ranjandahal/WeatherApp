<?php
function get_current_inventory($db) {
    $query = 'SELECT * FROM inventory';
    $statement = $db->prepare($query);
    $statement->execute();
    $inventory = $statement->fetchAll();
    $statement->closeCursor();
    return $inventory;  
}
function get_undelivered_orders($db) {
    $query = 'SELECT * FROM undelivered_orders';
    $statement = $db->prepare($query);
    $statement->execute(); 
    $undelivered_orders = $statement->fetchAll();
    $statement->closeCursor();    
    return $undelivered_orders;  
}

function add_undelivered_order($db, $order_id,$flour_qty,$cheese_qty) {
    $query = 'INSERT INTO undelivered_orders (order_id, flour_qty, cheese_qty) '
            . 'VALUES (:order_id,:flour_qty,:cheese_qty)';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id',$order_id);
    $statement->bindValue(':flour_qty',$flour_qty);
    $statement->bindValue(':cheese_qty',$cheese_qty);
    $statement->execute();
    $statement->closeCursor(); 
}

function update_inventory($db, $flour_qty, $cheese_qty) {
    //Update inventory by adding or deducting supplied quantities
    $query = 'UPDATE inventory SET flour_qty=flour_qty + :flour_qty, cheese_qty = cheese_qty + :cheese_qty';
    $statement = $db->prepare($query);
    $statement->bindValue(':flour_qty',$flour_qty);
    $statement->bindValue(':cheese_qty',$cheese_qty);
    $statement->execute();
    $statement->closeCursor();        
}

function delete_undelivered_orders($db, $order_id) {
    $query = 'DELETE FROM undelivered_orders WHERE order_id = :order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id',$order_id);
    $statement->execute();
    $statement->closeCursor();        
}


