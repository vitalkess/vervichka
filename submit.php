<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$name  = isset($_POST['name'])  ? trim($_POST['name'])  : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

$utm_source   = isset($_POST['utm_source'])   ? $_POST['utm_source']   : '';
$utm_medium   = isset($_POST['utm_medium'])   ? $_POST['utm_medium']   : '';
$utm_term     = isset($_POST['utm_term'])     ? $_POST['utm_term']     : '';
$utm_content  = isset($_POST['utm_content'])  ? $_POST['utm_content']  : '';
$utm_campaign = isset($_POST['utm_campaign']) ? $_POST['utm_campaign'] : '';

// Генеруємо order_id (11 цифр)
$order_id = substr(strval(round(microtime(true) * 100)), 0, 11);

// Масив товарів
$products_list = array(
    0 => array(
        'product_id' => '5',
        'price'      => '1750',
        'count'      => '1',
    )
);
$products = urlencode(serialize($products_list));

// Параметри для CRM
$data = array(
    'key'          => '8e884f7434e5383358d6ef5c5174492a',
    'order_id'     => $order_id,
    'country'      => 'UA',
    'products'     => $products,
    'bayer_name'   => $name,
    'phone'        => $phone,
    'utm_source'   => $utm_source,
    'utm_medium'   => $utm_medium,
    'utm_term'     => $utm_term,
    'utm_content'  => $utm_content,
    'utm_campaign' => $utm_campaign,
);

// Відправка в CRM
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://thischin.lp-crm.biz/api/addNewOrder.html');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);
$crm_response = curl_exec($curl);
curl_close($curl);

echo json_encode([
    'ok'  => true,
    'crm' => json_decode($crm_response, true),
]);
