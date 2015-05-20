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
 * File containing the Klarna_Checkout_Connector facade class
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
 * Connector factory
 *
 * @category  Payment
 * @package   Klarna_Checkout
 * @author    Rickard D. <rickard.dybeck@klarna.com>
 * @author    Christer G. <christer.gustavsson@klarna.com>
 * @author    David K. <david.keijser@klarna.com>
 * @copyright 2015 Klarna AB
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link      http://developers.klarna.com/
 */
class Klarna_Checkout_Connector
{
    /**
     * Domain of the testdrive
     */
    const BASE_TEST_URL = "https://checkout.testdrive.klarna.com";

    /**
     * Domain of the live system
     */
    const BASE_URL = "https://checkout.klarna.com";

    /**
     * Create a new Checkout Connector
     *
     * @param string $secret string used to sign requests
     * @param string $domain the domain used for requests
     *
     * @return Klarna_Checkout_ConnectorInterface
     */
    public static function create($secret, $domain = self::BASE_URL)
    {
        return new Klarna_Checkout_BasicConnector(
            Klarna_Checkout_HTTP_Transport::create(),
            new Klarna_Checkout_Digest,
            $secret,
            $domain
        );
    }
}
