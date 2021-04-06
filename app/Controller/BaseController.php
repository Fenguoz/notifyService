<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Redis\RedisFactory;
use Psr\Container\ContainerInterface;

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

    public function index()
    {
        $service = new \App\Service\NotifyService();

        $notifyDriver = 'Sms';
        $config = [
            'default' => [
                'gateways' => ['moduyun']
            ],
            'gateways' => [
                'moduyun' => [
                    'signId' => '****',
                    'accesskey' => '****',
                    'secretkey' => '****',
                ]
            ]
        ];

        $code = rand(100000, 999999);
        $params = [
            'template' => '****',
            'content' => '',
            'data' => [
                'code' => $code,
                'phone_number' => '18888888888'
            ],
        ];

        return $service->send($notifyDriver, $config, $params);
    }
}
