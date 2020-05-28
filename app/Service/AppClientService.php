<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\AppClient;
use Hyperf\Di\Annotation\Inject;

class AppClientService extends BaseService
{
    /**
     * @Inject
     * @var AppClient
     */
    protected $appClient;

    /**
     * 获取app客户端ID
     */
    public function getClientId(int $user_id, string $user_type): string
    {
        return $this->appClient->where([
            'user_id' => $user_id,
            'user_type' => $user_type,
        ])->value('client_id') ?? '';
    }

    /**
     * 更新app客户端ID
     */
    public function updateClientId(int $user_id, string $user_type, string $client_id): bool
    {
        $info = $this->appClient->where([
            'user_id' => $user_id,
            'user_type' => $user_type,
        ])->first();
        if (!$info) {
            $this->appClient->insert([
                'user_id' => $user_id,
                'user_type' => $user_type,
                'client_id' => $client_id,
            ]);
        }
        if ($info && $info->client_id != $client_id) {
            $info->client_id = $client_id;
            $info->save();
        }
        
        return true;
    }
}
