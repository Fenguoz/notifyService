<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NotifyService;
use Exception;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;
use Hyperf\Di\Annotation\Inject;

class WebSocketController extends BaseController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    /**
     * @Inject
     * @var NotifyService
     */
    protected $notifyService;

    /**
     * 发送消息
     * @param WebSocketServer $server
     * @param Frame $frame
     */
    public function onMessage(WebSocketServer $server, Frame $frame): void
    {

        if (empty($frame->data)) {
            $server->push($frame->fd, json_encode([
                'code' => 10000,
                'message' => '数据不能为空',
            ]));
            return;
        }
        $data = json_decode($frame->data, true);
        if (!isset($data['Authorization'])) {
            $server->push($frame->fd, json_encode([
                'code' => 10001,
                'message' => 'Authorization 不能为空',
            ]));
            return;
        }

        list($role, $token) = explode(' ', $data['Authorization']);
        $role = strtolower($role);
        if (!in_array($role, ['user', 'admin'])) {
            $server->push($frame->fd, json_encode([
                'code' => 10002,
                'message' => '非法角色'
            ]));
            return;
        }

        $mark = substr($token, -8);
        $frame_key = "ws_{$frame->fd}_{$mark}";
        $user_id = $this->redis->get($frame_key);
        if (!$user_id) {
            try {
                $response = $this->client->request('GET', config('user_url') . '/userinfo', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ]
                ]);
                $result = json_decode((string) $response->getBody(), true);
                if ($result['code'] != 200) throw new Exception($result['message']);
                $user_id = $result['data']['id'];
            } catch (Exception $e) {
                $server->push($frame->fd, json_encode([
                    'code' => 10003,
                    'message' => $e->getMessage(),
                ]));
                return;
            } finally {
                $user_key = "ws_{$role}_{$user_id}";
                $this->redis->set($frame_key, $user_id);
                $this->redis->set($user_key, $frame->fd);
            }
        }

        //刷新key
        $user_key = "ws_{$role}_{$user_id}";
        $this->redis->expire($frame_key, 7200);
        $this->redis->expire($user_key, 7200);

        $open_fd = $this->redis->get('ws_open_fd_' . $frame->fd);
        if($open_fd == 1){//推送上线
            $this->redis->set('ws_open_fd_' . $frame->fd, 0);
            $this->notifyService->send($user_id, $role, $user_id, $role, 0, 8);
        }
    }

    /**
     * 客户端失去链接
     * @param Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        $frame_like_key = "ws_{$fd}_*";
        $data = $this->redis->keys($frame_like_key);
        foreach ($data as $k => $frame_key) {
            $user_id = $this->redis->get($frame_key);
            $role = 'user';
            $user_key = "ws_{$role}_{$user_id}";
            $this->redis->del($user_key);
            $this->redis->del($frame_key);
            $this->redis->del('ws_open_fd_' . $fd);
        }
        var_dump('closed');
    }

    /**
     * 客户端链接
     * @param WebSocketServer $server
     * @param Request $request
     */
    public function onOpen(WebSocketServer $server, Request $request): void
    {
        $this->redis->set('ws_open_fd_' . $request->fd, 1);//标记新连接
    }
}
