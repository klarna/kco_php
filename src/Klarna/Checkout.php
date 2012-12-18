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
 * Bootstrap file to include the klarna checkout library
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

define('KLARNA_CHECKOUT_DIR', dirname(__file__) . '/Checkout');

require_once KLARNA_CHECKOUT_DIR . '/ConnectorInterface.php';
require_once KLARNA_CHECKOUT_DIR . '/ResourceInterface.php';
require_once KLARNA_CHECKOUT_DIR . '/Connector.php';
require_once KLARNA_CHECKOUT_DIR . '/BasicConnector.php';
require_once KLARNA_CHECKOUT_DIR . '/Order.php';
require_once KLARNA_CHECKOUT_DIR . '/Digest.php';
require_once KLARNA_CHECKOUT_DIR . '/Exception.php';
require_once KLARNA_CHECKOUT_DIR . '/ConnectionErrorException.php';
require_once KLARNA_CHECKOUT_DIR . '/ConnectorException.php';
require_once KLARNA_CHECKOUT_DIR . '/UserAgent.php';

require_once KLARNA_CHECKOUT_DIR . '/HTTP/TransportInterface.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/CURLHandleInterface.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/Request.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/Response.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/Transport.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/CURLTransport.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/CURLHeaders.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/CURLHandle.php';
require_once KLARNA_CHECKOUT_DIR . '/HTTP/CURLFactory.php';
