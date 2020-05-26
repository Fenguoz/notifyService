<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Testing;

use GuzzleHttp\Client;
use Hyperf\Contract\PackerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Packer\JsonPacker;
use Psr\Container\ContainerInterface;

class HttpClient
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var PackerInterface
     */
    protected $packer;

    public function __construct(ContainerInterface $container, PackerInterface $packer = null, $baseUri = 'http://127.0.0.1:9501')
    {
        $this->container = $container;
        $this->packer = $packer ?? new JsonPacker();
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => 2,
        ]);
    }

    public function get($uri, $data = [], $headers = [])
    {
        $response = $this->client->get($uri, [
            'headers' => $headers,
            'query' => $data,
        ]);
        return $this->packer->unpack($response->getBody()->getContents());
    }

    public function post($uri, $data = [], $headers = [])
    {
        $response = $this->client->post($uri, [
            'headers' => $headers,
            'form_params' => $data,
        ]);

        return $this->packer->unpack($response->getBody()->getContents());
    }

    public function json($uri, $data = [], $headers = [])
    {
        $headers['Content-Type'] = 'application/json';
        $response = $this->client->post($uri, [
            'json' => $data,
            'headers' => $headers,
        ]);

        return $this->packer->unpack($response->getBody()->getContents());
    }

    public function file($uri, $data = [], $headers = [])
    {
        $multipart = [];
        if (Arr::isAssoc($data)) {
            $data = [$data];
        }

        foreach ($data as $item) {
            $name = $item['name'];
            $file = $item['file'];

            $multipart[] = [
                'name' => $name,
                'contents' => fopen($file, 'r'),
                'filename' => basename($file),
            ];
        }

        $response = $this->client->post($uri, [
            'headers' => $headers,
            'multipart' => $multipart,
        ]);

        return $this->packer->unpack($response->getBody()->getContents());
    }
}
