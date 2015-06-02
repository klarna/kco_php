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
 * File containing the Klarna_Checkout_Resource class
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
 * Implementation of the order resource
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Majid G. <majid.garmaroudi@klarna.com>
 * @author    David K. <david.keijser@klarna.com>
 * @author    Matthias Feist <matthias.feist@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */
abstract class Klarna_Checkout_Resource
    implements Klarna_Checkout_ResourceInterface, ArrayAccess
{
    /**
     * Path that is used to create resources
     *
     * @var string
     */
    protected $relativePath = null;

    /**
     * Content Type to use
     *
     * @var string
     */
    protected $contentType = null;

    /**
     * Accept header to use
     *
     * @var string
     */
    protected $accept = null;

    /**
     * URI of remote resource
     *
     * @var string
     */
    protected $location;

    /**
     * Order data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Connector
     *
     * @var Klarna_Checkout_ConnectorInterface
     */
    protected $connector;

    /**
     * Create a new Resource object
     *
     * @param Klarna_Checkout_ConnectorInterface $connector connector to use
     */
    public function __construct(Klarna_Checkout_ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Get the URL of the resource
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
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
        $this->location = strval($location);
    }

    /**
     * Return content type of the resource
     *
     * @return string Content type
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Return accept header of the resource
     *
     * @return string Accept header
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set the content type
     *
     * @param string $contentType Content type
     *
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Set the accept type
     *
     * @param string $accept Accept type
     *
     * @return void
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
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
        $this->data = $data;
    }

    /**
     * Basic representation of the object
     *
     * @return array Data
     */
    public function marshal()
    {
        return $this->data;
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

        return isset($this->data[$key]) ? $this->data[$key] : null;
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

        $value = print_r($value, true);
        throw new RuntimeException(
            "Use update function to change values. trying to set $key to $value"
        );
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
        return array_key_exists($key, $this->data);
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
        throw new RuntimeException(
            "unset of fields not supported. trying to unset $key"
        );
    }
}
