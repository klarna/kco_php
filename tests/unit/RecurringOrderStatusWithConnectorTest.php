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
 * UnitTest for the RecurringOrder class, interactions with connector
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Matthias Feist <matthias.feist@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */
class Klarna_Checkout_RecurringOrderStatusWithConnectorTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * Order Instance
     *
     * @var Klarna_Checkout_RecurringOrder
     */
    protected $recurringOrder;

    /**
     * Connector Instance
     *
     * @var Klarna_Checkout_ConnectorStub
     */
    protected $connector;

    /**
     * Recurring Token
     *
     * @var string
     */
    protected $recurringToken = "123ABC";

    /**
     * Setup function
     *
     * @return void
     */
    public function setUp()
    {
        $this->connector = new Klarna_Checkout_ConnectorStub();
        $this->recurringOrder = new Klarna_Checkout_RecurringStatus(
            $this->connector,
            $this->recurringToken
        );
    }

    /**
     * Test that fetch works as intended
     *
     * @return void
     */
    public function testFetch()
    {
        $this->recurringOrder->setLocation("http://klarna.com/foo/bar/15");
        $url = $this->recurringOrder->getLocation();
        $this->recurringOrder->fetch();

        $this->assertEquals("GET", $this->connector->applied["method"]);
        $this->assertEquals(
            $this->recurringOrder,
            $this->connector->applied["resource"]
        );
        $this->assertArrayHasKey("url", $this->connector->applied["options"]);
        $this->assertEquals($url, $this->connector->applied["options"]["url"]);
    }
}