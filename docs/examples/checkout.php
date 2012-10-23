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
Klarna_Checkout_Order::$baseUri = 'https://klarna.apiary.io/checkout/orders';

session_start();

$connector = Klarna_Checkout_Connector::create('sharedSecret');

$order = null;
if (!array_key_exists('klarna_checkout', $_SESSION)) {
    // Start new session
    $banana = array(
        'type' => 'physical',
        'reference' => 'BANAN01',
        'name' => 'Bananana',
        'unit_price' => 450,
        'discount_rate' => 0,
        'tax_rate' => 2500
    );

    $shipping = array(
        'type' => 'shipping_fee',
        'reference' => 'SHIPPING',
        'name' => 'Shipping Fee',
        'unit_price' => 450,
        'discount_rate' => 0,
        'tax_rate' => 2500
    );

    $order = new Klarna_Checkout_Order(
        array(
            'purchase_country' => 'SE',
            'purchase_currency' => 'SEK',
            'locale' => 'sv-se',
            'merchant' => array(
                'id' => 2,
                'terms_uri' => 'http://localhost/terms.html',
                'checkout_uri' => 'http://localhost/checkout.php',
                'confirmation_uri' =>'http://localhost/thank-you.php',
                'push_uri' => 'http://localhost/push.php'
            ),
            'cart' => array(
                'total_price_including_tax' => 9000,
                'items' => array(
                    $banana,
                    $shipping
                )
            )
        )
    );
    $order->create($connector);
    $order->fetch($connector);
} else {
    // Resume session
    $order = new Klarna_Checkout_Order;
    $order->fetch($connector, $_SESSION['klarna_checkout']);
}

$snippet = $order['gui']['snippet'];
$_SESSION['klarna_checkout'] = $sessionId = $order->getLocation();
echo "<dl><dt>Session</dt><dd>{$sessionId}</dd</dl>";
echo "<div>{$snippet}</div>";
