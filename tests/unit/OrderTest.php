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
/**
 * UnitTest for the Order class, basic functionality
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
     * Setup function
     *
     * @return void
     */
    public function setUp()
    {
        $this->order = new Klarna_Checkout_Order();
    }

    /**
     * Test correct contentType is used
     *
     * @return void
     */
    public function testContentType()
    {
        $this->assertEquals(
            "application/vnd.klarna.checkout.aggregated-order-v2+json",
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
        $this->order[$key1] = $value1;

        $key2 = "testKey2";
        $value2 = "testValue2";
        $this->order[$key2] = $value2;

        $marshalData = $this->order->marshal();

        //Testing keys
        $this->assertArrayHasKey($key1, $marshalData);
        $this->assertArrayHasKey($key2, $marshalData);
        //Testing values
        $this->assertEquals($value1, $marshalData[$key1]);
        $this->assertEquals($value2, $marshalData[$key2]);
    }

    /**
     * Test that get and set work
     *
     * @return void
     */
    public function testSetGetValues()
    {
        $key = "testKey1";
        $this->order[$key] = "testValue1";

        $value2 = "testValue2";
        $this->order[$key] = $value2;

        $this->assertEquals($value2, $this->order[$key]);
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
        $this->order[$key] = $value;
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
        $this->order[$key];
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
        $this->order[$key];
    }
}
