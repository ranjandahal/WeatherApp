<?php
// Client-side REST, for pizza2 project
require_once('../util/main.php');
// use Composer autoloader, so we don't have to require Guzzle PHP files
require '../vendor/autoload.php';
require '../model/database.php';
require '../model/order_db.php';
// Now have $app_path, path from doc root to parent directory
// $app_path is of form /cs637/user/proj2/pizza2
// We want URL say http://topcat.cs.umb.edu/cs637/user/proj2/proj2_server/rest for REST service
// So drop "pizza2" from $app_path, add /proj2_server/rest
echo 'app_path: ' . $app_path . '<br>';
$spot = strpos($app_path, 'pizza2');
$part = substr($app_path, 0, $spot);
$base_url = $_SERVER['SERVER_NAME'] . $part . 'proj2_server/rest';
echo 'base_url: ' . $base_url . '<br>';

// Instantiate Guzzle HTTP client
$httpClient = new \GuzzleHttp\Client();

$url = 'http://' . $base_url . '/day/';
echo 'POST day = 3 to ' . $url . '<br>';
error_log('...... restclient: POST day = 3 to ' . $url);
try {
    $response = $httpClient->request('POST', $url, ['json' => 3]);
    $status = $response->getStatusCode();
} catch (GuzzleHttp\Exception $e) {
    $status = 'POST failed, error = ' . $e;
    error_log($status);
    include '../errors/error.php';  // Note new error.php code that handles Exceptions
}
?>
Post of day result: <?php echo $status ?> <br>
<?php
echo 'GET of day to ' . $url . '<br>';
error_log('...... restclient: GET day');
try {
    $response = $httpClient->get($url);
    echo 'Back from GET: day = ' . $response->getBody() . ' (wrong until server coded right) <br>';
} catch (Exception $e) {
    include '../errors/error.php'; 
}

$product_id = 1;
$url = 'http://' . $base_url . '/products/' . $product_id;
echo '<br>GET of product 1 to ' . $url . '<br>';
error_log('...... restclient: GET product');
try {
    $response = $httpClient->get($url);
    $prodJson = $response->getBody()->getContents();  // as StreamInterface, then string
    echo 'Returned result of GET of product 1: <br>';
    print_r($prodJson);
    echo '<br> After json_decode:<br>';
    $product = json_decode($prodJson, true);
    print_r($product);
} catch (Exception $e) {
    include '../errors/error.php'; 
}
?>

<p> Now POST it back, but change productCode to avoid uniqueness constraint </p>
<?php
$product_id = 177;
$url = 'http://' . $base_url . '/products/';
$product['productID'] = $product_id;
$product['productCode'] = 'strat02';  // works only once per each value

error_log('...... restclient: POST product');
try {
    // Guzzle does the json_encode for us--
    //$response2 = $httpClient->request('POST', $url, ['json' => $product]);
    //$location2 = $response2->getHeader('Location');
    //$status2 = $response2->getStatusCode();
} catch (Exception $e) {
  include '../errors/error.php'; 
}

if (isset($status2)) {
    echo "POST of product result: status = $status2 <br>";
    echo "Location = ";
    echo var_dump($location2) . '<br>';
}
?>
<!-- Location: array(1) { [0]=> string(65)
  "http://localhost/cs637/eoneil/proj2/proj2_server/rest/products/30" } -->
<br> If error is 400 Bad Request, it is probably because of constraint violation 
<br> on the unique column productCode preventing insert on the server side
<br> If so, you can fix it by changing productCode to a new value in the 
restclient/index.php code

<p> Now POST it back, but change order to avoid uniqueness constraint </p>
<p>---------------------------------------------------------------------</p>
<?php
$order = array('customerID' => 1, 'deliveryDay'=> 2, 
                        'order_items'=>array(array('product_code'=>'flour', 'quantity'=>1),
                                       array('product_code'=>'cheese', 'quantity'=>1))
                            );
$url = 'http://' . $base_url . '/orders/';

error_log('...... restclient: POST product');
try {
    // Guzzle does the json_encode for us--
    $body = $order;
    print_r($body);
    echo '\n';
    $order_id = add_orders($body['customerID'], date("Y-m-d h:i:sa"), 
                $body['deliveryDay']);
    echo 'CustomerID:' . $body['customerID'] . PHP_EOL;
    echo 'orderDate:' . date("Y-m-d h:i:sa") . PHP_EOL;
    echo 'deliveryDay:' . $body['deliveryDay'] . PHP_EOL;
    echo 'order_items:' ;
    print_r($body['order_items']);
    //echo PHP_EOL . 'order_items1:' ;
    //print_r($body['order_items'][0]);
    foreach($body['order_items'] as $items){
        echo 'product_code: ' . $items['product_code'];
        echo 'quantity: ' . $items['quantity'];
        $product_id = get_product_id($items['product_code']);
        print_r($product_id);
        $product = get_product($product_id);
        print_r($product);
        add_order_item($order_id, $product_id, $product['listPrice'], 
                        $product['listPrice']*($product['discountPercent']/100),  //Calculating discount amount
                        $items['quantity']);
    }
    print_r($order_id);
    exit();
    $response2 = $httpClient->request('POST', $url, ['json' => $order]);
    $location2 = $response2->getHeader('Location');
    $status2 = $response2->getStatusCode();
} catch (Exception $e) {
  include '../errors/error.php'; 
}

if (isset($status2)) {
    echo "POST of product result: status = $status2 <br>";
    echo "Location = ";
    echo var_dump($location2) . '<br>';
}
?>


<p> Now POST it back, --- Orders -- but change day to avoid uniqueness constraint </p>
<br> If error is 400 Bad Request, it is probably because of constraint violation 
<br> on the unique column productCode preventing insert on the server side
<br> If so, you can fix it by changing productCode to a new value in the 
restclient/index.php code

<p> Now POST it back, but change order-- to avoid uniqueness constraint </p>
<p>---------------------------------------------------------------------</p>
<?php
$order_id = 1;
$url = 'http://' . $base_url . '/orders/';

error_log('...... restclient: POST order');
try {
    // Guzzle does the json_encode for us--
    $response4 = $response = $httpClient->get($url);
    echo 'Back from GET: Order = ' . $response4->getBody() . ' (wrong until server coded right) <br>';
    
    error_log('...... restclient: GET order ALL');
    $response4 = $response = $httpClient->get($url . $order_id);
    echo 'Back from GET: Order = ' . $response4->getBody() . ' (wrong until server coded right) <br>';
} catch (Exception $e) {
  include '../errors/error.php'; 
}

if (isset($status2)) {
    echo "POST of product result: status = $status2 <br>";
    echo "Location = ";
    echo var_dump($location2) . '<br>';
}
?>
