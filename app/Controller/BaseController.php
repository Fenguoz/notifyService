<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use App\Constants\ErrorCode;
use App\Rpc\Types\NotifyCodeType;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Redis\RedisFactory;
use Psr\Container\ContainerInterface;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="XX项目Api",
 * ),
 * @OA\Server(
 *     url="http://127.0.0.1:9501",
 *     description="本地环境"
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

    public function index()
    {
        $service = new \App\Service\NotifyService();

        $code = rand(100000, 999999);
        var_dump($code);
        return $service->send(NotifyCodeType::Wechat, 'machine.apply', [
            'openid' => 'oAX5juIiq0w6rcgCOCkXjeWQNv1E',
            'data' => [
                'keyword1' => '哪个收到',
                'keyword2' => '推送消息',
                'keyword3' => '回复下',
                'keyword4' => '测试咨询时间',
            ],
            'miniprogram_path' => '/pages/user/user'
        ]);

        // return $service->send(NotifyCodeType::Sms, 'public.register', [
        //     'code' => $code,
        //     'phone_number' => '18883333901'
        // ]);

        // return $service->sendBatch(2, 1, [
        //     '243944672@qq.com' => [
        //         'code' => '321321'
        //     ],
        //     'zgf243944672@gmail.com' => [
        //         'code' => '123456'
        //     ]
        // ]);

        // return $service->send(3, 2, [
        //         'client_id' => 'af65dd9f41e93461b4d035a385eed4ff'
        // ]);

    }

    public function queue()
    {
        $action = $this->request->input('action');
        $data = $this->request->input('data');
        // $data = [
        //     'type_id' => 8,
        //     'target_id' => 123,
        //     'sender_id' => 779,
        //     'sender_type' => 'user',
        //     'receiver_id' => 784,
        //     'receiver_type' => 'admin',
        //     'ext_param' => [],
        // ];
        $service = new \App\Service\NotifyService();
        // return $service->queue($action, $data);
    }
}
