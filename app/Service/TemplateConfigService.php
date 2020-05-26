<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Template;
use App\Model\TemplateConfig;
use Hyperf\Di\Annotation\Inject;

class TemplateConfigService extends BaseService
{
    /**
     * @Inject
     * @var TemplateConfig
     */
    protected $templateConfig;

    /**
     * @Inject
     * @var Template
     */
    protected $template;

    /**
     * @Inject
     * @var TemplateService
     */
    protected $templateService;

    public function getList($where = [], $order = [], $page = 0, $limit = 0)
    {
        return $this->templateConfig->getList($where, $order, $page, $limit);
    }

    public function getInfo(int $id)
    {
        return $this->templateConfig->with('template')->find($id);
    }

    public function getTemplateContent(int $type_id, string $user_type = 'user', array $params = []): string
    {
        $template_id = $this->templateConfig->where([
            'type_id' => $type_id,
            'user_type' => $user_type,
        ])->value('template_id');
        if (!$template_id) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);

        return $this->templateService->replace($template_id, $params);
    }
}
