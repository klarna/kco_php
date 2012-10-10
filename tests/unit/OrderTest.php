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
 * File containing the Klarna_Checkout_Order unittest
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
require_once 'Checkout/ResourceInterface.php';
require_once 'Checkout/Order.php';
require_once 'Checkout/ConnectorInterface.php';
require_once 'tests/ConnectorStub.php';
/**
 * UnitTest for the Order class
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Majid G. <majid.garmaroudi@klarna.com>
 * @author    David K. <david.keijser@klarna.com>
 * @copyright 2012 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://integration.klarna.com/
 */
class Klarna_Checkout_OrderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Order Instance
     *
     * @var Klarna_Checkout_Order
     */
    protected $order;

    /**
     * Connector Instance
     *
     * @var Klarna_Checkout_ConnectorStub
     */
    protected $connector;

    /**
     * Setup function
     *
     * @return void
     */
    public function setUp()
    {
        $this->order = new Klarna_Checkout_Order();
        $this->connector = new Klarna_Checkout_ConnectorStub();
    }

    /**
     * Test correct contentType is used
     *
     * @return void
     */
    public function testContentType()
    {
        $this->assertEquals(
            "application/vnd.klarna.checkout.aggregated-order-v1+json",
            $this->order->getContentType()
        );
    }

    /**
     * Test that location is initialized as null
     *
     * @return void
     */
    public function testGetLocationEmpty()
    {
        $this->assertNull($this->order->getLocation());
    }

    /**
     * Test that location can be set
     *
     * @return void
     */
    public function testSetLocation()
    {
        $url = "http://foo";
        $this->order->setLocation($url);

        $this->assertEquals($url, $this->order->getLocation());
    }

    /**
     * Test that location's type is always string
     *
     * @return void
     */
    public function testSetLocationType()
    {
        $url = 5;
        $this->order->setLocation($url);

        $this->assertInternalType("string", $this->order->getLocation());
        $this->assertEquals(strval($url), $this->order->getLocation());
    }

    /**
     * Test that output of marshal works as input for parse
     *
     * @return void
     */
    public function testParseMarshalIdentity()
    {
        $data = array("foo" => "boo");
        $this->order->parse($data);

        $this->assertEquals($data, $this->order->marshal());
    }

    /**
     * Test that output of marshal works as input for parse
     *
     * @return void
     */
    public function testMarshalHasCorrectKeys()
    {
        $key1 = "testKey1";
        $value1 = "testValue1";
        $this->order->set($key1, $value1);

        $key2 = "testKey2";
        $value2 = "testValue2";
        $this->order->set($key2, $value2);

        $marshalData = $this->order->marshal();

        //Testing keys
        $this->assertArrayHasKey($key1, $marshalData);
        $this->assertArrayHasKey($key2, $marshalData);
        //Testing values
        $this->assertEquals($value1, $marshalData[$key1]);
        $this->assertEquals($value2, $marshalData[$key2]);
    }

    /**
     * Test that create works as intended
     *
     * @return void
     */
    public function testCreate()
    {
        $data = array("foo" => "boo");
        $order = Klarna_Checkout_Order::create($this->connector, $data);

        $this->assertInstanceOf("Klarna_Checkout_Order", $order);
        $this->assertInstanceOf("Klarna_Checkout_ResourceInterface", $order);
        $this->assertEquals("boo", $order->get("foo"));
        $this->assertEquals("POST", $this->connector->applied["method"]);
        $this->assertEquals($order, $this->connector->applied["resource"]);
        $this->assertEquals($order->getLocation(), "http://stub");
        $this->assertArrayHasKey("url", $this->connector->applied["options"]);
    }

    /**
     * Test that fetch works as intended
     *
     * @return void
     */
    public function testFetch()
    {
        $this->order->setLocation("http://klarna.com/foo/bar/15");
        $url = $this->order->getLocation();
        $this->order->fetch($this->connector);

        $this->assertEquals("GET", $this->connector->applied["method"]);
        $this->assertEquals($this->order, $this->connector->applied["resource"]);
        $this->assertArrayHasKey("url", $this->connector->applied["options"]);
        $this->assertEquals($url, $this->connector->applied["options"]["url"]);
    }

    /**
     * Test that update works as intended
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->order->setLocation("http://klarna.com/foo/bar/15");
        $url = $this->order->getLocation();
        $this->order->update($this->connector);

        $this->assertEquals("POST", $this->connector->applied["method"]);
        $this->assertEquals($this->order, $this->connector->applied["resource"]);
        $this->assertArrayHasKey("url", $this->connector->applied["options"]);
        $this->assertEquals($url, $this->connector->applied["options"]["url"]);
    }

    /**
     * Test that entry point (Base URL) can be changed
     *
     * @return void
     */
    public function testCreateAlternativeEntryPoint()
    {
        $data = array("foo" => "boo");
        $baseUrl = "https://checkout.klarna.com/beta/checkout/orders";
        Klarna_Checkout_Order::$baseUrl = $baseUrl;
        Klarna_Checkout_Order::create($this->connector, $data);

        $this->assertEquals($baseUrl, $this->connector->applied["options"]["url"]);
    }

    /**
     * Test that get and set work
     *
     * @return void
     */
    public function testSetGetValues()
    {
        $key = "testKey";
        $value = "testValue";
        $this->order->set($key, $value);

        $this->assertEquals($value, $this->order->get($key));
    }

    /**
     * Test that set overwrite the keys
     *
     * @return void
     */
    public function testSetGetValuesOverwrite()
    {
        $key = "testKey1";
        $this->order->set($key, "testValue1");

        $value2 = "testValue2";
        $this->order->set($key, $value2);

        $this->assertEquals($value2, $this->order->get($key));
    }

    /**
     * Test that set throw exception for invalid keys
     *
     * @return void
     */
    public function testSetInvalidKey()
    {
        $key = array("1"=>"2");
        $value = "testValue";

        $this->setExpectedException("InvalidArgumentException");
        $this->order->set($key, $value);
    }

    /**
     * Test that get throw exception for invalid keys
     *
     * @return void
     */
    public function testGetInvalidKey()
    {
        $key = array("1"=>"2");

        $this->setExpectedException("InvalidArgumentException");
        $this->order->get($key);
    }

    /**
     * Test that get throw exception for the key that doesn't exist
     *
     * @return void
     */
    public function testGetUnavailableKey()
    {
        $key = "test";

        $this->setExpectedException("OutOfBoundsException");
        $this->order->get($key);
    }
}