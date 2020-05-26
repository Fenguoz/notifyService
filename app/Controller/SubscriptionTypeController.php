<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Service\SubscriptionTypeService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\DeleteMapping;

/**
 * @Controller()
 */
class SubscriptionTypeController extends BaseController
{

    /**
     * @Inject
     * @var SubscriptionTypeService
     */
    protected $subscriptionTypeService;

    /**
     * @OA\Get(
     *     path="/get.subscription.list",
     *     operationId="list",
     *     tags={"订阅"},
     *     summary="获取订阅列表",
     *     description="获取订阅列表",
     *     @OA\Parameter(ref="#/components/parameters/count"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(ref="#/components/schemas/success")
     *     ),
     *     security={
     *          {"Authorization":{}}
     *     }
     * )
     */
    /**
     * @GetMapping(path="list")
     */
    public function list()
    {
        $page = (int) $this->request->input('page', 1);
        $count = (int) $this->request->input('count', 20);

        $data = $this->subscriptionTypeService->getList([], [], $page, $count);
        if (!$data) $this->error(ErrorCode::NO_DATA);
        return $this->success($data);
    }
}
