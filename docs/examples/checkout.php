<?php
/**
 * Copyright 2012 Klarna AB
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
 * PHP version 5.3
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Klarna <support@klarna.com>
 * @copyright 2012 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://integration.klarna.com/
 */

require_once 'src/Klarna/Checkout.php';

// Array containing the cart items
$cart = array(
    array(
        'quantity' => 1,
        'reference' => 'BANAN01',
        'name' => 'Bananana',
        'unit_price' => 450,
        'discount_rate' => 0,
        'tax_rate' => 2500
    ),
    array(
        'quantity' => 1,
        'type' => 'shipping_fee',
        'reference' => 'SHIPPING',
        'name' => 'Shipping Fee',
        'unit_price' => 450,
        'discount_rate' => 0,
        'tax_rate' => 2500
    )
);

// Merchant ID
$eid = 2;

// Shared secret
$sharedSecret = 'sharedSecret';
///

Klarna_Checkout_Order::$baseUri = 'https://klarnacheckout.apiary.io/checkout/orders';
Klarna_Checkout_Order::$contentType
    = "application/vnd.klarna.checkout.aggregated-order-v2+json";

session_start();

$connector = Klarna_Checkout_Connector::create($sharedSecret);

$order = null;
if (array_key_exists('klarna_checkout', $_SESSION)) {
    // Resume session
    $order = new Klarna_Checkout_Order;
    try {
        $order->fetch($connector, $_SESSION['klarna_checkout']);


        // Reset cart
        $order['cart']['items'] = array();
        foreach ($cart as $item) {
            $order['cart']['items'][] = $item;
        }
        $order->update($connector);
    } catch (Exception $e) {
        // Reset session
        $order = null;
        unset($_SESSION['klarna_checkout']);
    }
}

if ($order == null) {
    // Start new session

    $order = new Klarna_Checkout_Order();
    $order['purchase_country'] = 'SE';
    $order['purchase_currency'] = 'SEK';
    $order['locale'] = 'sv-se';
    $order['merchant']['id'] = $eid;
    $order['merchant']['terms_uri'] = 'http://localhost/terms.html';
    $order['merchant']['checkout_uri'] = 'http://localhost/checkout.php';
    $order['merchant']['confirmation_uri'] = 'http://localhost/confirmation.php';
    // You can not recieve push notification on non publicly available uri
    $order['merchant']['push_uri'] = 'http://localhost/push.php' .
        '?checkout_uri={checkout.order.uri}';

    foreach ($cart as $item) {
        $order['cart']['items'][] = $item;
    }

    $order->create($connector);
    $order->fetch($connector);
}

// Store location of checkout session
$_SESSION['klarna_checkout'] = $sessionId = $order->getLocation();

// Display checkout
$snippet = $order['gui']['snippet'];
// DESKTOP: Width of containing block shall be at least 750px
// MOBILE: Width of containing block shall be 100% of browser window (No
// padding or margin)
echo "<div>{$snippet}</div>";
