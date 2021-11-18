<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Kernel\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;

class Request
{
    protected $timeout = 20.0;
    /**
     * @var bool
     */
    protected $verify = false;
    /**
     * @var string
     */
    protected $baseUri;

    public function __construct()
    {
        $this->timeout = config('newlink.http.timeout', 20.0);
    }

    /**
     * 初始化Client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function baseClient(): Client
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);

        return new Client([
            'timeout' => $this->timeout,
            'verify' => $this->verify,
            'handler' => $stack,
        ]);
    }

    /**
     * init Options.
     *
     * @param array $options
     * @return array
     */
    protected function initOptions($options): array
    {
        if (! is_array($options)) {
            throw new InvalidArgumentException('options must be array');
        }

        if(get_class($this->app) == "Zhr\NewLink\Platform\Application"){
            $options = $this->getOilOptions($options);
        }elseif(get_class($this->app) == "Zhr\NewLink\Electricity\Application"){
            $options = $this->getElectricityOptions($options);
        }

        return $options;
    }

    /**
     * make request.
     *
     * @param string $url
     * @param string $method
     * @param array $options
     * @return \GuzzleHttp\Psr7\Response
     */
    public function request($url, $method = 'GET', $options = [], $returnRaw = false): Response
    {
        $method = strtoupper($method);

        if (property_exists($this, 'baseUri') && ! is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        return $this->baseClient()->request($method, $url, $this->initOptions($options));
    }


    /**
     * getOilOptions
     *
     * @param array $options
     * @return string
     */
    protected function getOilOptions($options)
    {
        // 按照字母方式排序
        ksort($options['query']);
        $_sign = $this->app_secret;
        foreach ($options['query'] as $key => $value) {
            $_sign .= $key . $value;
        }
        $options['query']['sign'] = md5($_sign . $this->app_secret);
        return $options;
    }

    /**
     * getElectricityOptions
     *
     * @param array $options
     * @return string
     */
    protected function getElectricityOptions($options)
    {
        // 按照字母方式排序
        ksort($options['query']);
        $private_key = openssl_pkey_get_private(file_get_contents(config('newlink.electricity.private_key')));
        $query = '';
        foreach ($options['query'] as $key => $value) {
            $query .= '&' . $key .'='. $value;
        }
        $query = substr($query, 1);
        openssl_sign($query, $crypted, $private_key, OPENSSL_ALGO_MD5);
        $options['query']['sig'] = base64_encode($crypted);
        return $options;
    }
}
