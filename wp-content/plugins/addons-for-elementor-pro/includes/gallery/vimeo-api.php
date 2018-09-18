<?php
/**
 *   Copyright 2013 Vimeo
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */

class LAE_Vimeo_API
{
    const ROOT_ENDPOINT = 'https://api.vimeo.com';
    const AUTH_ENDPOINT = 'https://api.vimeo.com/oauth/authorize';
    const ACCESS_TOKEN_ENDPOINT = '/oauth/access_token';
    const CLIENT_CREDENTIALS_TOKEN_ENDPOINT = '/oauth/authorize/client';
    const VERSIONS_ENDPOINT = '/versions';
    const VERSION_STRING = 'application/vnd.vimeo.*+json; version=3.4';
    const USER_AGENT = 'vimeo.php 2.0.0; (http://developer.vimeo.com/api/docs)';
    const CERTIFICATE_PATH = '/certificates/vimeo-api.pem';

    protected $_curl_opts = array();
    protected $CURL_DEFAULTS = array();

    private $_client_id = null;
    private $_client_secret = null;
    private $_access_token = null;

    /**
     * Creates the Vimeo library, and tracks the client and token information.
     *
     * @param string $client_id Your applications client id. Can be found on developer.vimeo.com/apps
     * @param string $client_secret Your applications client secret. Can be found on developer.vimeo.com/apps
     * @param string $access_token Your applications client id. Can be found on developer.vimeo.com/apps or generated using OAuth 2.
     */
    public function __construct($client_id, $client_secret, $access_token = null)
    {
        $this->_client_id = $client_id;
        $this->_client_secret = $client_secret;
        $this->_access_token = $access_token;
        $this->CURL_DEFAULTS = array(
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            //Certificate must indicate that the server is the server to which you meant to connect.
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => realpath(__DIR__ .'/../..') . self::CERTIFICATE_PATH
        );
    }

    /**
     * Make an API request to Vimeo.
     *
     * @param string $url A Vimeo API Endpoint. Should not include the host
     * @param array $params An array of parameters to send to the endpoint. If the HTTP method is GET, they will be added to the url, otherwise they will be written to the body
     * @param string $method The HTTP Method of the request
     * @param bool $json_body
     * @param array $headers An array of HTTP headers to pass along with the request.
     * @return array This array contains three keys, 'status' is the status code, 'body' is an object representation of the json response body, and headers are an associated array of response headers
     */
    public function request($url, $params = array(), $method = 'GET', $json_body = true, array $headers = array())
    {
        $headers = array_merge(array(
            'Accept' => self::VERSION_STRING,
            'User-Agent' => self::USER_AGENT,
        ), $headers);

        $method = strtoupper($method);

        // add bearer token, or client information
        if (!empty($this->_access_token)) {
            $headers['Authorization'] = 'Bearer ' . $this->_access_token;
        } else {
            //  this may be a call to get the tokens, so we add the client info.
            $headers['Authorization'] = 'Basic ' . $this->_authHeader();
        }

        //  Set the methods, determine the URL that we should actually request and prep the body.
        $curl_opts = array();
        switch ($method) {
            case 'GET':
                if (!empty($params)) {
                    $query_component = '?' . http_build_query($params, '', '&');
                } else {
                    $query_component = '';
                }

                $curl_url = self::ROOT_ENDPOINT . $url . $query_component;
                break;

            case 'POST':
            case 'PATCH':
            case 'PUT':
            case 'DELETE':
                if ($json_body && !empty($params)) {
                    $headers['Content-Type'] = 'application/json';
                    $body = json_encode($params);
                } else {
                    $body = http_build_query($params, '', '&');
                }

                $curl_url = self::ROOT_ENDPOINT . $url;
                $curl_opts = array(
                    CURLOPT_POST => true,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => $body
                );
                break;
        }

        // Set the headers
        foreach ($headers as $key => $value) {
            $curl_opts[CURLOPT_HTTPHEADER][] = sprintf('%s: %s', $key, $value);
        }

        $response = $this->_request($curl_url, $curl_opts);

        $response['body'] = json_decode($response['body'], true);

        return $response;
    }

    /**
     * Request the access token associated with this library.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_access_token;
    }

    /**
     * Assign a new access token to this library.
     *
     * @param string $access_token the new access token
     */
    public function setToken($access_token)
    {
        $this->_access_token = $access_token;
    }

    /**
     * Gets custom cURL options.
     *
     * @return string
     */
    public function getCURLOptions()
    {
        return $this->_curl_opts;
    }

    /**
     * Sets custom cURL options.
     *
     * @param array $curl_opts An associative array of cURL options.
     */
    public function setCURLOptions($curl_opts = array())
    {
        $this->_curl_opts = $curl_opts;
    }

    /**
     * Convert the raw headers string into an associated array
     *
     * @param string $headers
     * @return array
     */
    public static function parse_headers($headers)
    {
        $final_headers = array();
        $list = explode("\n", trim($headers));

        $http = array_shift($list);

        foreach ($list as $header) {
            $parts = explode(':', $header, 2);
            $final_headers[trim($parts[0])] = isset($parts[1]) ? trim($parts[1]) : '';
        }

        return $final_headers;
    }

    /**
     * Request an access token. This is the final step of the
     * OAuth 2 workflow, and should be called from your redirect url.
     *
     * @param string $code The authorization code that was provided to your redirect url
     * @param string $redirect_uri The redirect_uri that is configured on your app page, and was used in buildAuthorizationEndpoint
     * @return array This array contains three keys, 'status' is the status code, 'body' is an object representation of the json response body, and headers are an associated array of response headers
     */
    public function accessToken($code, $redirect_uri)
    {
        return $this->request(self::ACCESS_TOKEN_ENDPOINT, array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri
        ), "POST", false);
    }

    /**
     * Get client credentials for requests.
     *
     * @param mixed $scope Scopes to request for this token from the server.
     * @return array Response from the server with the tokens, we also set it into this object.
     */
    public function clientCredentials($scope = 'public')
    {
        if (is_array($scope)) {
            $scope = implode(' ', $scope);
        }

        $token_response = $this->request(self::CLIENT_CREDENTIALS_TOKEN_ENDPOINT, array(
            'grant_type' => 'client_credentials',
            'scope' => $scope
        ), "POST", false);

        return $token_response;
    }

    /**
     * Build the url that your user.
     *
     * @param string $redirect_uri The redirect url that you have configured on your app page
     * @param string $scope An array of scopes that your final access token needs to access
     * @param string $state A random variable that will be returned on your redirect url. You should validate that this matches
     * @return string
     */
    public function buildAuthorizationEndpoint($redirect_uri, $scope = 'public', $state = null)
    {
        $query = array(
            "response_type" => 'code',
            "client_id" => $this->_client_id,
            "redirect_uri" => $redirect_uri
        );

        $query['scope'] = $scope;
        if (empty($scope)) {
            $query['scope'] = 'public';
        } elseif (is_array($scope)) {
            $query['scope'] = implode(' ', $scope);
        }

        if (!empty($state)) {
            $query['state'] = $state;
        }

        return self::AUTH_ENDPOINT . '?' . http_build_query($query);
    }

    /**
     * Internal function to handle requests, both authenticated and by the upload function.
     *
     * @param string $url
     * @param array $curl_opts
     * @return array
     */
    private function _request($url, $curl_opts = array())
    {
        // Merge the options (custom options take precedence).
        $curl_opts = $this->_curl_opts + $curl_opts + $this->CURL_DEFAULTS;

        // Call the API.
        $curl = curl_init($url);
        curl_setopt_array($curl, $curl_opts);
        $response = curl_exec($curl);
        $curl_info = curl_getinfo($curl);

        if (isset($curl_info['http_code']) && $curl_info['http_code'] === 0) {
            $curl_error = curl_error($curl);
            $curl_error = !empty($curl_error) ? ' [' . $curl_error .']' : '';
            throw new VimeoRequestException('Unable to complete request.' . $curl_error);
        }

        curl_close($curl);

        // Retrieve the info
        $header_size = $curl_info['header_size'];
        $headers = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        // Return it raw.
        return array(
            'body' => $body,
            'status' => $curl_info['http_code'],
            'headers' => self::parse_headers($headers)
        );
    }

    /**
     * Get authorization header for retrieving tokens/credentials.
     *
     * @return string
     */
    private function _authHeader()
    {
        return base64_encode($this->_client_id . ':' . $this->_client_secret);
    }
}