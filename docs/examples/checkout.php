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

session_start();

$connector = Klarna_Checkout_Connector::create('sharedSecret');

$order = null;
if (!array_key_exists('klarna_checkout', $_SESSION)) {
    // Start new session
    $banana = array(
        'type' => 'undefined',
        'reference' => 'BANAN01',
        'name' => 'Bananana',
        'unit_price' => 450,
        'discount_rate' => 0,
        'tax_rate' => 2500
    );

    $shipping = array(
        'type' => 'undefined',
        'reference' => 'SHIPPING',
        'name' => 'Shipping Fee',
        'unit_price' => 450,
        'discount_rate' => 0,
        'tax_rate' => 2500
    );

    $order = new Klarna_Checkout_Order(
        array(
            'merchant_id' => 2,
            'purchase_country' => 'SWE',
            'purchase_currency' => 'SEK',
            'locale' => 'sv-se',
            'merchant_tac_uri' => 'http://localhost/terms.html',
            'merchant_tac_title' => 'Lelles ved',
            'merchant_checkout_uri' => 'http://localhost/checkout',
            'merchant_callback_uri' => 'http://localhost/thankyou',
            'merchant_push_uri' => 'http://localhost/callback',
            'cart' => array(
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
    $order->fetch($_SESSION['klarna_checkout']);
}

$snippet = $order['client_snippet'];
$_SESSION['klarna_checkout'] = $sessionId = $order->getLocation();
echo "<dl><dt>Session</dt><dd>{$sessionId}</dd</dl>";
echo "<div>{$snippet}</div>";
