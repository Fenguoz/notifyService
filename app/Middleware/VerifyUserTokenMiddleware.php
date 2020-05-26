<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Context;

/**
 * VerifyUserTokenMiddleware
 * @package App\Middleware
 */
class VerifyUserTokenMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = $container->get(ClientFactory::class)->create();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 判断是否登录
        if (!isset($request->getHeader('authorization')[0])) throw new BusinessException(ErrorCode::AUTHORIZATION_EMPTY);
        try {
            $response = $this->client->request('GET', config('oauth_url') . '/get.user.info', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $request->getHeader('authorization')[0],
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
            if ($data['code'] != 200) throw new BusinessException($data['code'], $data['message']);
        } catch (ServerException $e) {
            throw new BusinessException($e->getCode(), $e->getMessage());
        } catch (ClientException $e) {
            throw new BusinessException($e->getCode(), $e->getMessage());
        } catch (BusinessException $e) {
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

        Context::set('user_id',(int) $data['data']['user_info']['id']);
        Context::set('client_id',(int) $data['data']['client_id']);
        return $handler->handle($request);
    }
}
