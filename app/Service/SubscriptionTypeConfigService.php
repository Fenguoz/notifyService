<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\SubscriptionTypeConfig;
use Hyperf\Di\Annotation\Inject;

class SubscriptionTypeConfigService extends BaseService
{
    /**
     * @Inject
     * @var SubscriptionTypeConfig
     */
    protected $subscriptionTypeConfig;

    public function getList($where = [], $order = ['id' => 'DESC'], $page = 0, $limit = 0)
    {
        return $this->subscriptionTypeConfig->getList($where, $order, $page, $limit);
    }

    public function getInfoById(int $id)
    {
        return $this->subscriptionTypeConfig->find($id);
    }
}
