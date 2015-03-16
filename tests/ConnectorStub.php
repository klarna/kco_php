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
 * File containing the Klarna_Checkout_ConnectorStub class
 *
 * PHP version 5.3
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Klarna <support@klarna.com>
 * @copyright 2012 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */

/**
 * Stub implementation of the Connector
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Majid G. <majid.garmaroudi@klarna.com>
 * @author    David K. <david.keijser@klarna.com>
 * @copyright 2012 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */
class Klarna_Checkout_ConnectorStub implements Klarna_Checkout_ConnectorInterface
{
    public $applied;

    public $location;

    /**
     * Applying the method on the specific resource
     *
     * @param string                            $method   Http methods
     * @param Klarna_Checkout_ResourceInterface $resource Resource
     * @param array                             $options  Options
     *
     * @return void
     */
    public function apply(
        $method,
        Klarna_Checkout_ResourceInterface $resource,
        array $options = null
    ) {
        $this->applied = array(
            "method" => $method,
            "resource" => $resource,
            "options" => $options
        );

        if ($this->location !== null) {
            $resource->setLocation($this->location);
        }
    }

    /**
     * Gets the underlying transport object
     *
     * @return void
     */
    public function getTransport()
    {
    }
}
