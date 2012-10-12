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
 * File containing the PHPUnit Klarna_HTTP_CURLTest test case
 *
 * PHP version 5.2
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage Unit_Tests
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://integration.klarna.com/
 */

require_once 'Checkout/HTTP/HTTPInterface.php';
require_once 'Checkout/HTTP/CURL.php';
require_once 'Checkout/HTTP/Request.php';

/**
 * PHPUnit test case for the HTTP CURL wrapper.
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage Unit_Tests
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://integration.klarna.com/
 */
class Klarna_Checkout_HTTP_CURLTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Klarna_HTTP_CURL
     */
    protected $http;

    /**
     * Set up resources used for each test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->http = new Klarna_Checkout_HTTP_CURL();
    }

    /**
     * Clears the resources used between tests.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->http = null;
    }

    /**
     * Make sure that the correct interface(s) are implemented.
     *
     * @return void
     */
    public function testInterface()
    {
        $this->assertInstanceOf('Klarna_Checkout_HTTP_HTTPInterface', $this->http);
    }

    /**
     * Make sure that the initial state is correct.
     *
     * @return void
     */
    public function testInit()
    {
        $this->assertEquals(5, $this->http->getTimeout());
    }

    /**
     * Make sure that createRequest returns a usuable request object.
     *
     * @return void
     */
    public function testCreateRequest()
    {
        $request = $this->http->createRequest('url');
        $this->assertInstanceOf('Klarna_Checkout_HTTP_Request', $request);
        $this->assertEquals('url', $request->getURL());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('', $request->getData());
        $this->assertEquals(0, count($request->getHeaders()));
    }
}
