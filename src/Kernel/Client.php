<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Kernel;

use Zhr\NewLink\Kernel\Http\Request;

class Client extends Request
{
    /**
     * ServiceContainer.
     *
     * @var \Zhr\NewLink\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * Client constructor.
     *
     * @param \Zhr\NewLink\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        parent::__construct();

        $this->app = $app;
    }

    /**
     * Http Get.
     *
     * @param string $url
     * @param array $query
     * @return mixed
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * Http Post.
     *
     * @param string $url
     * @param array $query
     * @return mixed
     */
    public function httpPost(string $url, array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query]);
    }

    /**
     * Http Post Json.
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function httpPostJson(string $url, array $query = [], array $json = [])
    {
        return $this->request($url, 'POST', [
            'query' => $query,
            'json' => $json,
        ]);
    }

    /**
     * Http get redirect.
     *
     * @param string $url
     * @return mixed
     */
    public function httpGetRedirect(string $url)
    {
        return $this->request($url, 'GET', [
            'allow_redirects' => [
                'track_redirects' => true,
            ],
        ]);
    }

    /**
     * Http Post Upload video for mp4.
     *
     * @param string $url
     * @param array $query
     * @param string $video_path
     * @return mixed
     */
    public function httpPostUpload($url, array $query, string $video_path)
    {
        $options = [
            'query' => $query,
            'multipart' => [
                [
                    'name' => 'video',
                    'contents' => fopen($video_path, 'r'),
                    'headers' => [
                        'Content-Type' => 'video/mp4',
                    ],
                ],
            ],
        ];

        return $this->request($url, 'POST', $options);
    }
}
