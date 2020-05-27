<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Setting;
use App\Service\NotifyService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\Utils\Context;

/**
 * @Controller()
 */
class NotifyController extends BaseController
{
    /**
     * @Inject
     * @var NotifyService
     */
    protected $notifyService;

    /**
     * @OA\Get(
     *     path="/get.notify.list",
     *     operationId="list",
     *     tags={"消息"},
     *     summary="获取消息列表",
     *     description="获取消息列表",
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

        $data = $this->notifyService->getList([], [], $page, $count);
        return $this->success($data);
    }

    /**
     * @OA\Post(
     *     path="/post.usersend",
     *     operationId="userSend",
     *     tags={"消息"},
     *     summary="消息发送（用户角色）",
     *     description="消息发送",
     *     @OA\Parameter(ref="#/components/parameters/target_id"),
     *     @OA\Parameter(ref="#/components/parameters/subscription_type_id"),
     *     @OA\Parameter(ref="#/components/parameters/template_param"),
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
     * @PostMapping(path="userSend")
     */
    public function userSend()
    {
        $user_id = Context::get('user_id');
        $target_id = (int) $this->request->input('target_id', 0);
        $type_id = (int) $this->request->input('type_id', 0);
        $template_param = (string) $this->request->input('template_param', '');

        $result = $this->notifyService->send($user_id, 'user', $user_id, 'user', $target_id, $type_id, (!empty($template_param) ? json_decode($template_param, true) : []));
        return $this->success($result);
    }

    /**
     * @OA\Post(
     *     path="/post.adminsend",
     *     operationId="adminSend",
     *     tags={"消息"},
     *     summary="消息发送（管理员角色）",
     *     description="消息发送",
     *     @OA\Parameter(ref="#/components/parameters/receiver_id"),
     *     @OA\Parameter(ref="#/components/parameters/target_id"),
     *     @OA\Parameter(ref="#/components/parameters/subscription_type_id"),
     *     @OA\Parameter(ref="#/components/parameters/template_param"),
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
     * @PostMapping(path="adminSend")
     */
    public function adminSend()
    {
        $user_id = Context::get('admin_id');
        $receiver_id = (int) $this->request->input('receiver_id', 0);
        // $receiver_type = $this->request->input('receiver_type', 'user');
        $target_id = (int) $this->request->input('target_id', 0);
        $type_id = (int) $this->request->input('type_id', 0);
        $template_param = (string) $this->request->input('template_param', '');

        $result = $this->notifyService->send($user_id, 'admin', $receiver_id, 'user', $target_id, $type_id, (!empty($template_param) ? json_decode($template_param, true) : []));
        return $this->success($result);
    }
}
