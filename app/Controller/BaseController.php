<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use App\Constants\ErrorCode;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="消息服务APi",
 * ),
 * @OA\Server(
 *     url="http://127.0.0.1:9501",
 *     description="本地"
 * )
 */
/**
 * @Controller()
 */
class BaseController extends AbstractController
{

    /**
     * @var ContainerInterfase
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->redis = $container->get(RedisFactory::class)->get('default');
        $this->client = $container->get(ClientFactory::class)->create();
    }

    /**
     * success
     * 成功返回请求结果
     * @param array $data
     * @param string|null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success($data = [], string $msg = null)
    {
        $msg = $msg ?? ErrorCode::getMessage(ErrorCode::SUCCESS);
        $data = [
            'code' => ErrorCode::SUCCESS,
            'message' => $msg,
            'data' => $data
        ];

        return $this->response->json($data);
    }

    /**
     * error
     * 业务相关错误结果返回
     * @param int $code
     * @param string|null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function error(int $code = ErrorCode::ERR_SERVER, string $msg = null)
    {
        $msg = $msg ?? ErrorCode::getMessage($code);
        $data = [
            'code' => $code,
            'message' => $msg,
        ];

        return $this->response->json($data);
    }
}
