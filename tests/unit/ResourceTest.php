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
 * File containing the Klarna_Checkout_Order unittest
 *
 * PHP version 5.3
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Klarna <support@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */

/**
 * UnitTest for the Resource class, basic functionality
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Matthias Feist <matthias.feist@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */
class Klarna_Checkout_ResourceTest extends PHPUnit_Framework_TestCase
{

    /**
     * Resource provider for all resources
     *
     * @return array
     */
    public function resourceProvider()
    {
        $mock = $this->getMock('Klarna_Checkout_ConnectorInterface');

        return array(
            array(new Klarna_Checkout_Order($mock)),
            array(new Klarna_Checkout_RecurringOrder($mock, "ABC")),
            array(new Klarna_Checkout_RecurringStatus($mock, "ABC"))
        );
    }

    /**
     * Test correct contentType is used
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testContentType($resource)
    {
        $resource->setContentType("application/json");
        $this->assertEquals("application/json", $resource->getContentType());
    }

    /**
     * Test that location can be set
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testSetLocation($resource)
    {
        $url = "http://foo";
        $urlInt = 5;

        $resource->setLocation($url);
        $this->assertEquals($url, $resource->getLocation());
        $resource->setLocation($urlInt);
        $this->assertInternalType("string", $resource->getLocation());
    }

    /**
     * Test that output of marshal works as input for parse
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testParseMarshalIdentity($resource)
    {
        $data = array("foo" => "boo");

        $resource->parse($data);
        $this->assertEquals($data, $resource->marshal());
    }

    /**
     * Test that get and set work
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testGetValues($resource)
    {
        $key = "testKey1";
        $value = "testValue1";

        $resource->parse(array($key => $value));
        $this->assertEquals($value, $resource[$key]);

    }

    /**
     * Test that set throw exception for invalid keys
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testSetInvalidKey($resource)
    {
        $key = array("1" => "2");
        $value = "testValue";

        $this->setExpectedException("InvalidArgumentException");

        $resource[$key] = $value;
    }

    /**
     * Test that get throw exception for invalid keys
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testGetInvalidKey($resource)
    {
        $key = array("1" => "2");

        $this->setExpectedException("InvalidArgumentException");

        $resource[$key];
    }

    /**
     * Test receiving an unavailable key
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testGetUnavailableKey($resource)
    {
        $key = "test";

        $this->assertFalse(isset($resource[$key]));
    }

    /**
     * Test that setAccept works
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testSetAccept($resource)
    {
        $resource->setAccept("application/json");
        $this->assertEquals("application/json", $resource->getAccept());
    }

    /**
     * Test that setting of keys is not allowed on resources
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testSetKey($resource)
    {
        $key = "foo";
        $value = "bar";

        $this->setExpectedException("RuntimeException");

        $resource[$key] = $value;
    }

    /**
     * Test that unsetting of keys is not allowed on resources
     *
     * @param Klarna_Checkout_Resource $resource Resource to be tested
     *
     * @dataProvider resourceProvider
     *
     * @return void
     */
    public function testUnsetKey($resource)
    {
        $key = "foo";

        $this->setExpectedException("RuntimeException");

        unset($resource[$key]);
    }
}
