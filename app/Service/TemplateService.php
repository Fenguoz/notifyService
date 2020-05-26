<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Template;
use Hyperf\Di\Annotation\Inject;

class TemplateService extends BaseService
{
    /**
     * @Inject
     * @var Template
     */
    protected $template;

    public function getList($where = [], $order = ['id' => 'DESC'], $page = 0, $limit = 0)
    {
        return $this->template->getList($where, $order, $page, $limit);
    }

    public function getInfoById(int $id)
    {
        return $this->template->find($id);
    }

    public function replace(int $template_id, array $params = []): string
    {
        $info = $this->getInfoById($template_id);
        if (!$info) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);
        if (!$params) return $info->content;

        $symbol = '#';
        $pattern = $symbol . '[a-zA-Z0-9_]+' . $symbol;
        preg_match_all($pattern, $info->content, $variable);
        if (!$variable[0]) return $info->content;

        foreach ($params as $key => $value) {
            if (in_array($key, $variable[0])) {
                $replace_data[($symbol . $key . $symbol)] = $value;
            }
        }

        return str_replace(array_keys($replace_data), $replace_data, $info->content);
    }
}
