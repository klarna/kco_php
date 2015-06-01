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
 * Example of a create recurring order call.
 *
 * PHP version 5.3.4
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Matthias Feist <matthias.feist@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */

/*
 Note! First you must have created a regular aggregated order with
 the option "recurring" set to true.
 After that order has received either status "checkout_complete" or
 "created" you can fetch that resource and retrieve the "recurring_token"
 property which is needed to create recurring orders.
 */

require_once 'src/Klarna/Checkout.php';

$eid = '0';
$sharedSecret = 'sharedSecret';
$recurringToken = "ABC123";

$connector = Klarna_Checkout_Connector::create(
    $sharedSecret,
    Klarna_Checkout_Connector::BASE_TEST_URL
);

$recurringOrder = new Klarna_Checkout_RecurringOrder($connector, $recurringToken);

// If the order should be activated automatically.
// Set to true if you instead want a invoice created
// otherwise you will get a reservation.
$create['activate'] = true;

$create['purchase_currency'] = 'SEK';
$create['purchase_country'] = 'SE';
$create['locale'] = 'sv-se';
$create['merchant']['id'] = $eid;
$create['cart'] = array();

$address = array(
    "postal_code" => "12345",
    "email" => "checkout-se@testdrive.klarna.com",
    "country" => "se",
    "city" => "Ankeborg",
    "family_name" => "Approved",
    "given_name" => "Testperson-se",
    "street_address" => "Stårgatan 1",
    "phone" => "070 111 11 11"
);
$create["billing_address"] = $address;
$create["shipping_address"] = $address;

$create["merchant_reference"] = array(
    "orderid1" => "someUniqueId..."
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

$create['cart']['items'] = array();

foreach ($cart as $item) {
    $create['cart']['items'][] = $item;
}

try {
    $recurringOrder->create($create);
} catch (Klarna_Checkout_ApiErrorException $e) {
    var_dump($e->getMessage());
    var_dump($e->getPayload());
    die;
}

var_dump($recurringOrder);
