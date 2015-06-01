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
 * File containing the Klarna_Checkout_RecurringOrder class
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
 * Implementation of the recurring order resource
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Matthias Feist <matthias.feist@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */
class Klarna_Checkout_RecurringOrder extends Klarna_Checkout_Resource implements
    Klarna_Checkout_ResourceCreateableInterface
{
    /**
     * Path that is used to create resources
     *
     * @var string
     */
    protected $relativePath = '/checkout/recurring/%s/orders';

    /**
     * Content Type to use
     *
     * @var string
     */
    protected $contentType
        = "application/vnd.klarna.checkout.recurring-order-v1+json";

    /**
     * Accept header to use
     *
     * @var string
     */
    protected $accept
        = 'application/vnd.klarna.checkout.recurring-order-accepted-v1+json';

    /**
     * Create a new recurring order object
     *
     * @param Klarna_Checkout_ConnectorInterface $connector      connector to use
     * @param string                             $recurringToken recurring token
     */
    public function __construct(
        Klarna_Checkout_ConnectorInterface $connector,
        $recurringToken
    ) {
        parent::__construct($connector);

        $uri = $this->connector->getDomain() . sprintf(
            $this->relativePath,
            $recurringToken
        );

        $this->setLocation($uri);
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
            'url' => $this->location,
            'data' => $data
        );

        $this->connector->apply('POST', $this, $options);
    }
}
