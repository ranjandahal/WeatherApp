<?php include '../../view/header.php'; ?>
<main>
    <section>
    <h1>Current Orders Report</h1>
        <h2>Orders Baked but not delivered</h2>
        <?php if (count($baked_orders) > 0): ?>
            <?php foreach ($baked_orders as $baked_order) : ?>
                <?php echo " ID:" . $baked_order['id']; ?>
                <?php echo "Room number:" . $baked_order['room_number']; ?><br>  
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Baked orders</p>
        <?php endif; ?>

        <h2>Orders Preparing(in the oven): Any ready now?</h2>
        <?php if (count($preparing_orders) > 0): ?>
            <?php foreach ($preparing_orders as $preparing_order) : ?>
                <?php echo "ID:" . $preparing_order['id']; ?> 
                <?php echo "Room number:" . $preparing_order['room_number']; ?> <br> 
             <?php endforeach; ?>
        <?php else: ?>
            <p>No orders are being prepared in Oven</p>
        <?php endif; ?>
            <h2>Supplies on Order</h2>
        <?php if (count($current_undelivered_orders) > 0): ?>
            <table>
                <?php foreach ($current_undelivered_orders as $undelivered_orders) : ?>
                    <tr>
                        <td><?php echo 'Order ' . $undelivered_orders['order_id'] . ': '; ?> </td>
                        <td><?php echo 'flour ' . $undelivered_orders['flour_qty']; ?> </td>  
                        <td><?php echo ' cheese ' . $undelivered_orders['cheese_qty']; ?> </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No Supplies on Order </p>
        <?php endif; ?>
            <h2>Current Inventory</h2>
        <?php if (count($current_supplies) > 0): ?>
            <table>
                <?php foreach ($current_supplies as $current_supply) : ?>
                    <tr><td><?php echo 'flour'; ?></td>
                        <td><?php echo $current_supply['flour_qty']; ?> </td> 
                    </tr> 
                    <tr><td><?php echo ' cheese'; ?> </td>
                        <td><?php echo $current_supply['cheese_qty']; ?> </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No Inventory Left</p>
        <?php endif; ?>
        <br> 
        <form  action="index.php" method="post" >
            <input type="hidden" name="action" value="change_to_baked">
            <input type="submit" value="Mark Oldest Pizza Baked" />
            <br>
        </form>
        <form  action="index.php" method="post" >
            <input type="hidden" name="action" value="order_supply">
            <input type="submit" value="Order Supplies" style="display:none;" />
            <br>
        </form>
        <br>  

    </section>
</main>
<?php include '../../view/footer.php'; 
