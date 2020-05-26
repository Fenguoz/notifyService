<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Model\Subscription;
use App\Service\SubscriptionService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\DeleteMapping;

/**
 * @Controller()
 */
class SubscriptionController extends BaseController
{

    /**
     * @Inject
     * @var SubscriptionService
     */
    protected $subscriptionService;

    /**
     * @Inject
     * @var Subscription
     */
    protected $subscription;

    /**
     * @OA\Get(
     *     path="/get.my.subscription",
     *     operationId="my",
     *     tags={"订阅"},
     *     summary="获取我的订阅",
     *     description="获取我的订阅",
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
     * @GetMapping(path="my")
     */
    public function my()
    {
        $user_id = Context::get('user_id');
        $page = (int) $this->request->input('page', 1);
        $count = (int) $this->request->input('count', 20);

        $data = $this->subscriptionService->getList(['user_id' => $user_id], ['id' => 'DESC'], $page, $count);
        if (!$data) $this->error(ErrorCode::NO_DATA);
        return $this->success($data);
    }

    /**
     * @OA\Post(
     *     path="/post.subscribing",
     *     operationId="subscribing",
     *     tags={"订阅"},
     *     summary="订阅",
     *     description="订阅",
     *     @OA\Parameter(ref="#/components/parameters/target_id"),
     *     @OA\Parameter(ref="#/components/parameters/subscription_type"),
     *     @OA\Parameter(ref="#/components/parameters/subscription_type_id"),
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
     * @PostMapping(path="subscribing")
     */
    public function subscribing()
    {
        $user_id = Context::get('user_id');
        $target_id = (int) $this->request->input('target_id', 0);
        $type = $this->request->input('type', 'event');
        $type_id = (int) $this->request->input('type_id', 0);

        if ($target_id <= 0) return $this->error(ErrorCode::PARAMS_ERROR);

        $data = $this->subscriptionService->subscribing($user_id, 'user', $target_id, $type_id, $type);
        if (!$data) $this->error(ErrorCode::ADD_ERROR);

        return $this->success(true);
    }

    /**
     * @OA\Delete(
     *     path="/delete.subscription",
     *     operationId="delete",
     *     tags={"订阅"},
     *     summary="取消订阅",
     *     description="取消订阅",
     *     @OA\Parameter(ref="#/components/parameters/id"),
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
     * @DeleteMapping(path="delete")
     */
    public function delete()
    {
        $id = (int) $this->request->input('id');
        if ($id <= 0) return $this->error(ErrorCode::PARAMS_ERROR);

        $result = $this->subscriptionService->delete($id);
        return $this->success($result);
    }
}
