<?php

function initial_db($db) {
    $query = 'delete from order_topping;';
    $query.= 'delete from pizza_orders;';
    $query.= 'delete from sizes;';
    $query.= 'delete from toppings;';
    $query.= 'delete from pizza_sys_tab;';
    $query.= 'truncate table inventory;';              //Invenotry table truncate that reseeds identity column 
    $query.= 'truncate table undelivered_orders;';     //Undeliverd table delete that reseeds identity column 
    $query.= 'insert into pizza_sys_tab values (1);';
    $query.= "insert into toppings values (1,'Pepperoni');";
    $query.= "insert into sizes values (1,'Small');";
    $query.= "insert into sizes values (2,'Large');";
    $query.= "insert into sizes values (3,'Medium');";
    // TODO: reinitialize inventory, undelivered orders tables
    $query.= 'insert into inventory values (150,150);';  //Initial inventory Flour: 150, Cheese: 150
    $statement = $db->prepare($query);
    $statement->execute();

    return $statement;
}
