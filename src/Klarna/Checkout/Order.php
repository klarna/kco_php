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
 * File containing the Klarna_Checkout_Order class
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

/**
 * Implementation of the order resource
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Majid G. <majid.garmaroudi@klarna.com>
 * @author    David K. <david.keijser@klarna.com>
 * @copyright 2012 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://integration.klarna.com/
 */
class Klarna_Checkout_Order
    implements Klarna_Checkout_ResourceInterface, ArrayAccess
{
    /**
     * Base URL that is used to create order resources
     *
     * @var string
     */
    public static $baseUrl = "https://checkout.klarna.com/checkout/orders";

    /**
     * Content Type to use
     *
     * @var string
     */
    public static $contentType = null;

    /**
     * URL of remote resource
     *
     * @var string
     */
    private $_location;

    /**
     * Order data
     *
     * @var array
     */
    private $_data = array();

    /**
     * Create a new Order object
     *
     * @param array $data initial data
     *
     * @return void
     */
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->_data = $data;
        }
    }

    /**
     * Get the URL of the resource
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->_location;
    }

    /**
     * Set the URL of the resource
     *
     * @param string $location URL of the resource
     *
     * @return void
     */
    public function setLocation($location)
    {
        $this->_location = strval($location);
    }

    /**
     * Return content type of the resource
     *
     * @return string Content type
     */
    public function getContentType()
    {
        return self::$contentType;
    }

    /**
     * Replace resource data
     *
     * @param array $data data
     *
     * @return void
     */
    public function parse(array $data)
    {
        $this->_data = $data;
    }

    /**
     * Basic representation of the object
     *
     * @return array Data
     */
    public function marshal()
    {
        return $this->_data;
    }

    /**
     * Create a new order
     *
     * @param Klarna_Checkout_ConnectorInterface $connector An instance of
     *                                                      connector class
     *
     * @return void
     */
    public function create(
        Klarna_Checkout_ConnectorInterface $connector
    ) {
        $options = array(
            'url' => self::$baseUrl
        );

        $connector->apply('POST', $this, $options);
    }

    /**
     * Fetch order data
     *
     * @param Klarna_Checkout_ConnectorInterface $connector An instance of
     *                                                      connector class
     * @param string                             $location  optional url
     *
     * @return void
     */
    public function fetch(
        Klarna_Checkout_ConnectorInterface $connector,
        $location = null
    ) {
        if ($location !== null) {
            $this->setLocation($location);
        }
        $options = array(
            'url' => $this->_location
        );
        $connector->apply('GET', $this, $options);
    }

    /**
     * Update order data
     *
     * @param Klarna_Checkout_ConnectorInterface $connector An instance of
     *                                                      connector class
     *
     * @return void
     */
    public function update(Klarna_Checkout_ConnectorInterface $connector)
    {
        $options = array(
            'url' => $this->_location
        );
        $connector->apply('POST', $this, $options);
    }

    /**
     * Get value of a key
     *
     * @param string $key Key
     *
     * @return mixed data
     */
    public function offsetGet($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Key must be string");
        }
        if (!array_key_exists($key, $this->_data)) {
            throw new OutOfBoundsException("{$key} doesn't exist");
        }
        return $this->_data[$key];
    }

    /**
     * Set value of a key
     *
     * @param string $key   Key
     * @param mixed  $value Value of the key
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Key must be string");
        }
        $this->_data[$key] = $value;
    }

    /**
     * Check if a key exists in the resource
     *
     * @param string $key key
     *
     * @return boolean
     */
    public function offsetExists($key)
    {
        return array_key_exists($this->_data, $key);
    }

    /**
     * Unset the value of a key
     *
     * @param string $key key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->_data[$key]);
    }
}
