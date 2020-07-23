<?php
/**
 * Copyright 2015 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Example of a checkout page
 *
 * PHP version 5.3.4
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Klarna <support@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */

require_once 'src/Klarna/Checkout.php';

session_start();

$order = null;
$eid = '0';
$sharedSecret = 'sharedSecret';

$connector = Klarna_Checkout_Connector::create(
    $sharedSecret,
    Klarna_Checkout_Connector::BASE_TEST_URL
);

$cart = array(
    array(
        'reference' => '123456789',
        'name' => 'Klarna t-shirt',
        'quantity' => 2,
        'unit_price' => 12300,
        'discount_rate' => 1000,
        'tax_rate' => 2500
    ),
    array(
        'type' => 'shipping_fee',
        'reference' => 'SHIPPING',
        'name' => 'Shipping Fee',
        'quantity' => 1,
        'unit_price' => 4900,
        'tax_rate' => 2500
    )
);

if (array_key_exists('klarna_order_id', $_SESSION)) {
    // Resume session
    $order = new Klarna_Checkout_Order(
        $connector,
        $_SESSION['klarna_order_id']
    );

    try {
        $order->fetch();

        // Reset cart
        $update['cart']['items'] = array();

        foreach ($cart as $item) {
            $update['cart']['items'][] = $item;
        }

        $order->update($update);
    } catch (Exception $e) {
        // Reset session
        $order = null;
        unset($_SESSION['klarna_order_id']);
    }
}

if ($order == null) {
    // Start new session
    $create['purchase_country'] = 'SE';
    $create['purchase_currency'] = 'SEK';
    $create['locale'] = 'sv-se';
    $create['merchant'] = array(
        'id' => $eid,
        'terms_uri' => 'http://example.com/terms.html',
        'checkout_uri' => 'http://example.com/checkout.php',
        'confirmation_uri' => 'http://example.com/confirmation.php' .
            '?klarna_order_id={checkout.order.id}',
        // You can not receive push notification on non publicly available URI
        'push_uri' => 'http://example.com/push.php' .
            '?klarna_order_id={checkout.order.id}'
    );
    $update['cart']['items'] = array();

    foreach ($cart as $item) {
        $create['cart']['items'][] = $item;
    }

    $order = new Klarna_Checkout_Order($connector);

    try {
        $order->create($create);
        $order->fetch();
    } catch (Klarna_Checkout_ApiErrorException $e) {
        var_dump($e->getMessage());
        var_dump($e->getPayload());
        die;
    }
}

// Store location of checkout session
$_SESSION['klarna_order_id'] = $order['id'];

if (isset($order['gui']['snippet'])) {
    // Display checkout
    $snippet = $order['gui']['snippet'];
    // DESKTOP: Width of containing block shall be at least 750px
    // MOBILE: Width of containing block shall be 100% of browser window (No
    // padding or margin)
    echo "<div>{$snippet}</div>";
}
