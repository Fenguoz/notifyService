<?php

namespace App\Service;

use App\Constants\ErrorCode;
use Driver\Notify\NotifyFactory;
use Hyperf\Amqp\Producer;
use Hyperf\Utils\ApplicationContext;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\Server;

/**
 * @Info(
 *     version="1.0",
 *     title="Notify｜Microservice",
 * ),
 * @Server(
 *     url="http://127.0.0.1:9701",
 *     description="本地"
 * )
 */
abstract class BaseService
{
    /**
     * @var ContainerInterfase
     */
    protected $container;

    public function __construct()
    {
        $this->container = ApplicationContext::getContainer();
        $this->producer = $this->container->get(Producer::class);
        $this->notify = $this->container->get(NotifyFactory::class);
    }

    /**
     * success
     */
    public function success($data = [], string $msg = null)
    {
        return [
            'code' => ErrorCode::SUCCESS,
            'message' => $msg ?? ErrorCode::getMessage(ErrorCode::SUCCESS),
            'data' => $data
        ];
    }

    /**
     * error
     */
    public function error(int $code = ErrorCode::ERR_SERVER, string $msg = null)
    {
        return [
            'code' => $code,
            'message' => $msg ?? ErrorCode::getMessage($code),
        ];
    }
}