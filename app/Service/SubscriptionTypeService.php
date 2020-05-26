<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\SubscriptionType;
use Hyperf\Di\Annotation\Inject;

class SubscriptionTypeService extends BaseService
{
    /**
     * @Inject
     * @var SubscriptionType
     */
    protected $subscriptionType;

    public function getList($where = [], $order = ['id' => 'DESC'], $page = 0, $limit = 0)
    {
        return $this->subscriptionType->getList($where, $order, $page, $limit);
    }

    public function getInfoById(int $id)
    {
        return $this->subscriptionType->find($id);
    }
}
