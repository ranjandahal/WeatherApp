<?php include '../../view/header.php'; ?>
<main>
    <section>
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
            
        <h1>Supply Orders Form</h1>
    <form  action="index.php" method="get">
        <input type="hidden" name="action" value="order_placed">
        Flour Unit:
        <input type='number' name="flour_qty" min="1" max="250" value="1">
        </br></br>
        Cheese Unit:
        <input type='number' name="cheese_qty" min="1" max="250" value="1"><br><br>
        <input type="submit" value="Order Supplies" /> <br><br>
    </form>
        <br>  

    </section>
</main>
<?php include '../../view/footer.php'; 