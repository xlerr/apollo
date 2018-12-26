<?php

namespace apollo;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Apollo
 *
 * @package apollo
 * @see     https://github.com/ctripcorp/apollo/wiki
 */
class Apollo
{
    /**
     * @var string
     */
    public $envs;

    /**
     * @var string
     */
    public $apps;

    /**
     * @var string
     */
    public $clusters;

    /**
     * @var string
     */
    public $namespaces;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $user;

    /**
     * @var array
     */
    public $requestOptions = [
        'headers' => [
            'Content-Type' => 'application/json;charset=UTF-8',
        ],
    ];

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $httpClientOptions = [];

    public function __construct($baseUri = null, $options = [])
    {
        if (null !== $baseUri) {
            $options = array_merge($this->httpClientOptions, $options, [
                'base_uri' => rtrim($baseUri, '/') . '/',
            ]);

            $this->httpClient = new Client($options);
        }
    }

    /**
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @return \GuzzleHttp\Client|\GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $comment
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($key, $value, $comment)
    {
        $uri = $this->getUri();

        $options = $this->buildOptions([
            'json' => [
                'key'                 => $key,
                'value'               => $value,
                'comment'             => $comment,
                'dataChangeCreatedBy' => $this->user,
            ],
        ]);

        $response = $this->httpClient->post($uri, $options);

        return $this->parseResponse($response);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $comment
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update($key, $value, $comment)
    {
        $uri = $this->getUri($key);

        $options = $this->buildOptions([
            'json' => [
                'key'                      => $key,
                'value'                    => $value,
                'comment'                  => $comment,
                'dataChangeLastModifiedBy' => $this->user,
            ],
        ]);

        $response = $this->httpClient->put($uri, $options);

        return $this->parseResponse($response);
    }

    /**
     * @param string $key
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete($key)
    {
        $uri = $this->getUri($key);

        $options = $this->buildoptions([
            'query' => [
                'operator' => $this->user,
            ],
        ]);

        $response = $this->httpClient->delete($uri, $options);

        return $this->parseResponse($response);
    }

    /**
     * @param      $title
     * @param null $comment
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function releases($title, $comment = null)
    {
        $uri = $this->getUri(null, 'releases');

        $options = $this->buildoptions([
            'json' => [
                'releaseTitle'   => $title,
                'releaseComment' => $comment,
                'releasedBy'     => $this->user,
            ],
        ]);

        $response = $this->httpClient->post($uri, $options);

        return $this->parseResponse($response);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function parseResponse(ResponseInterface $response)
    {
        return $response;
    }

    /**
     * @param null   $key
     * @param string $flag
     *
     * @return string
     */
    protected function getUri($key = null, $flag = 'items')
    {
        $uri = vsprintf('envs/%s/apps/%s/clusters/%s/namespaces/%s/%s', [
            $this->envs,
            $this->apps,
            $this->clusters,
            $this->namespaces,
            $flag,
        ]);

        if (null !== $key) {
            $uri .= '/' . $key;
        }

        return $uri;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function buildOptions($options = [])
    {
        return array_merge($this->requestOptions, [
            'headers' => [
                'Authorization' => $this->token,
            ],
        ], $options);
    }
}
