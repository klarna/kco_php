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
 * File containing the Klarna_Checkout_HTTP_CURL class
 *
 * PHP version 5.2
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage HTTP
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://integration.klarna.com/
 */

require_once 'HTTPInterface.php';
require_once 'CURLHeaders.php';
require_once 'Request.php';
require_once 'Response.php';

/**
 * Klarna HTTP implementation for cURL
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage HTTP
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://integration.klarna.com/
 */
class Klarna_Checkout_HTTP_CURL implements Klarna_Checkout_HTTP_HTTPInterface
{
    /**
     * Number of seconds before the connection times out.
     *
     * @var int
     */
    protected $timeout;

    /**
     * Initializes a new instance of the HTTP cURL class.
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new RuntimeException(
                'cURL extension is requred.'
            );
        }

        $this->timeout = 5; // default to 5 seconds
    }

    /**
     * Sets the number of seconds until a connection times out.
     *
     * @param int $timeout number of seconds
     *
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = intval($timeout);
    }

    /**
     * Gets the number of seconds before the connection times out.
     *
     * @return int timeout in number of seconds
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Performs a HTTP request.
     *
     * @param Klarna_HTTP_Request $request the HTTP request to send.
     *
     * @throws RuntimeException                Thrown if a cURL handle cannot
     *                                         be initialized.
     * @throws Klarna_ConnectionErrorException Thrown for unspecified network
     *                                         or hardware issues.
     * @return Klarna_HTTP_Response
     */
    public function send(Klarna_Checkout_HTTP_Request $request)
    {
        $curl = curl_init();
        if ($curl === false) {
            throw new RuntimeException(
                'Failed to initialize a HTTP handle.'
            );
        }

        $url = $request->getURL();
        curl_setopt($curl, CURLOPT_URL, $url);

        $method = $request->getMethod();
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getData());
        }

        // Convert headers to cURL format.
        $requestHeaders = array();
        foreach ($request->getHeaders() as $key => $value) {
            $requestHeaders[] = $key . ': ' . $value;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeaders);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);

        $curlHeaders = new Klarna_Checkout_HTTP_CURLHeaders();
        curl_setopt(
            $curl, CURLOPT_HEADERFUNCTION,
            array(&$curlHeaders, 'processHeader')
        );

        // TODO remove me when real cert is in place
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $payload = curl_exec($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);

        /*
         * A failure occured if:
         * payload is false (e.g. HTTP timeout?).
         * info is false, then it has no HTTP status code.
         */
        if ($payload === false || $info === false) {
            throw new Klarna_Checkout_ConnectionErrorException(
                "Connection to '{$url}' failed."
            );
        }

        $headers = $curlHeaders->getHeaders();

        // Convert Content-Type into a normal header
        $headers['Content-Type'] = $info['content_type'];

        $response = new Klarna_Checkout_HTTP_Response(
            $request, $headers, intval($info['http_code']), strval($payload)
        );

        return $response;
    }

    /**
     * Creates a HTTP request object.
     *
     * @param string $url the request URL.
     *
     * @throws InvalidArgumentException If the specified argument
     *                                  is not of type string.
     * @return Klarna_HTTP_Request
     */
    public function createRequest($url)
    {
        return new Klarna_Checkout_HTTP_Request($url);
    }
}
