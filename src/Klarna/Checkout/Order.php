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
 * File containing the Klarna_Checkout_Order class
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
class Klarna_Checkout_Order extends Klarna_Checkout_Resource implements
    Klarna_Checkout_ResourceCreateableInterface,
    Klarna_Checkout_ResourceFetchableInterface,
    Klarna_Checkout_ResourceUpdateableInterface
{
    /**
     * Path that is used to create resources
     *
     * @var string
     */
    protected $relativePath = '/checkout/orders';

    /**
     * Content Type to use
     *
     * @var string
     */
    protected $contentType
        = "application/vnd.klarna.checkout.aggregated-order-v2+json";

    /**
     * Create a new order.
     *
     * @param Klarna_Checkout_ConnectorInterface $connector connector to use
     * @param string                             $id        Order id
     */
    public function __construct(
        Klarna_Checkout_ConnectorInterface $connector,
        $id = null
    ) {
        parent::__construct($connector);

        if ($id !== null) {
            $uri = $this->connector->getDomain() . "{$this->relativePath}/{$id}";
            $this->setLocation($uri);
        }
    }

    /**
     * Create a new order
     *
     * @param array $data data to initialise order resource with
     *
     * @return void
     */
    public function create(array $data)
    {
        $options = array(
            'url' => $this->connector->getDomain() . $this->relativePath,
            'data' => $data
        );

        $this->connector->apply('POST', $this, $options);
    }

    /**
     * Fetch order data
     *
     * @return void
     */
    public function fetch()
    {
        $options = array(
            'url' => $this->location
        );
        $this->connector->apply('GET', $this, $options);
    }

    /**
     * Update order data
     *
     * @param array $data data to update order resource with
     *
     * @return void
     */
    public function update(
        array $data
    ) {
        $options = array(
            'url' => $this->location,
            'data' => $data
        );
        $this->connector->apply('POST', $this, $options);
    }
}
