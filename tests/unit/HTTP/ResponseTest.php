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
 * File containing the PHPUnit Klarna_HTTP_ResponseTest test case
 *
 * PHP version 5.2
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage Unit_Tests
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://developers.klarna.com/
 */

/**
 * PHPUnit test case for the HTTP Response object.
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage Unit_Tests
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://developers.klarna.com/
 */
class Klarna_Checkout_HTTP_ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Make sure that the initial state is correct.
     *
     * @return void
     */
    public function testConstructor()
    {
        $request = new Klarna_Checkout_HTTP_Request('url');

        $response = new Klarna_Checkout_HTTP_Response(
            $request, array('test' => 'value'), 203, 'my data'
        );

        $this->assertSame($request, $response->getRequest());

        $header = $response->getHeader('test');
        $this->assertInternalType('string', $header);
        $this->assertEquals('value', $header);
        $this->assertEquals(array('test' => 'value'), $response->getHeaders());
        $this->assertNull($response->getHeader('undefined'));

        $status = $response->getStatus();
        $this->assertInternalType('int', $status);
        $this->assertEquals(203, $status);

        $data = $response->getData();
        $this->assertInternalType('string', $data);
        $this->assertEquals('my data', $data);
    }

    /**
     * Make sure that getHeader works regardless of case
     *
     * @return void
     */
    public function testGetHeaderCaseInsensitive()
    {
        $request = new Klarna_Checkout_HTTP_Request('url');
        $response = new Klarna_Checkout_HTTP_Response(
            $request, array('Test' => 'value'), 203, 'my data'
        );

        $this->assertEquals('value', $response->getHeader('TEST'));
        $this->assertEquals('value', $response->getHeader('Test'));
        $this->assertEquals('value', $response->getHeader('test'));
    }
}
