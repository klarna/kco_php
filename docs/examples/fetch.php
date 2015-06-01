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
 * Example of a fetch call.
 *
 * PHP version 5.3.4
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    David Keijser <david.keijser@klarna.com>
 * @author    Rickard Dybeck <rickard.dybeck@klarna.com>
 * @author    Matthias Feist <matthias.feist@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */

require_once 'src/Klarna/Checkout.php';

$sharedSecret = 'sharedSecret';
$orderID = 'ABC123';

$connector = Klarna_Checkout_Connector::create(
    $sharedSecret,
    Klarna_Checkout_Connector::BASE_TEST_URL
);

$order = new Klarna_Checkout_Order($connector, $orderID);

try {
    $order->fetch();
} catch (Klarna_Checkout_ApiErrorException $e) {
    var_dump($e->getMessage());
    var_dump($e->getPayload());
    die;
}

var_dump($order);
