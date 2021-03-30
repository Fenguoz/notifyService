<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\NotifyTemplateConfig;
use App\Rpc\NotifyTemplateConfigServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Response;

/**
 * @RpcService(name="NotifyTemplateConfigService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyTemplateConfigService extends BaseService implements NotifyTemplateConfigServiceInterface
{
    /**
     * @Post(
     *     path="/notify_template_config/getList",
     *     operationId="getList",
     *     tags={"NotifyTemplateConfigService"},
     *     summary="消息模版配置列表",
     *     description="消息模版配置列表",
     *     @Parameter(ref="#/components/parameters/where_obj"),
     *     @Parameter(ref="#/components/parameters/order_obj"),
     *     @Parameter(ref="#/components/parameters/count"),
     *     @Parameter(ref="#/components/parameters/page"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/notify_template_config")
     *     ),
     *     @Response(
     *         response=10005,
     *         description="NO_DATA",
     *         @JsonContent(ref="#/components/schemas/no_data")
     *     )
     * )
     */
    public function getList(
        array $where = [],
        array $order = ['id' => 'DESC'],
        int $count = 0,
        int $page = 1
    ) {
        $list = (new NotifyTemplateConfig)->getList($where, $order, $count, $page);
        if (!$list) {
            throw new BusinessException(ErrorCode::NO_DATA);
        }

        return $this->success($list);
    }

    /**
     * @Post(
     *     path="/notify_template_config/getPagesInfo",
     *     operationId="getPagesInfo",
     *     tags={"NotifyTemplateConfigService"},
     *     summary="消息模版配置分页信息",
     *     description="消息模版配置分页信息",
     *     @Parameter(ref="#/components/parameters/where_obj"),
     *     @Parameter(ref="#/components/parameters/count"),
     *     @Parameter(ref="#/components/parameters/page"),
     *     @Response(
     *         response=200,
     *         description="操作成功",
     *         @JsonContent(ref="#/components/schemas/pageInfo")
     *     )
     * )
     */
    public function getPagesInfo(array $where = [], int $count = 10, int $page = 1)
    {
        $pageInfo = (new NotifyTemplateConfig)->getPagesInfo($where, $count, $page);
        return $this->success($pageInfo);
    }

    /**
     * @Post(
     *     path="/notify_template_config/add",
     *     operationId="add",
     *     tags={"NotifyTemplateConfigService"},
     *     summary="消息模版配置文章",
     *     description="消息模版配置文章",
     *     @Parameter(ref="#/components/parameters/params_obj"),
     *     @Response(
     *         response=200,
     *         description="操作成功",
     *         @JsonContent(ref="#/components/schemas/success")
     *     ),
     *     @Response(
     *         response=10001,
     *         description="ADD_FAIL",
     *         @JsonContent(ref="#/components/schemas/error")
     *     )
     * )
     */
    public function add(array $params)
    {
        if (!isset($params['notify_code']) || empty($params['notify_code'])) {
            throw new BusinessException(ErrorCode::NOTIFY_CODE_REQUIRED);
        }
        if (!isset($params['template_id']) || empty($params['template_id'])) {
            throw new BusinessException(ErrorCode::TEMPLATE_REQUIRED);
        }
        if (!isset($params['action_id']) || empty($params['action_id'])) {
            throw new BusinessException(ErrorCode::ACTION_REQUIRED);
        }

        $data = $this->_checkData($params);
        $result = NotifyTemplateConfig::create($data);
        if (!$result)
            throw new BusinessException(ErrorCode::ADD_ERROR);
        return $this->success();
    }

    /**
     * @Post(
     *     path="/notify_template_config/edit",
     *     operationId="edit",
     *     tags={"NotifyTemplateConfigService"},
     *     summary="编辑消息模版配置",
     *     description="编辑消息模版配置",
     *     @Parameter(ref="#/components/parameters/params_obj"),
     *     @Response(
     *         response=200,
     *         description="操作成功",
     *         @JsonContent(ref="#/components/schemas/success")
     *     ),
     *     @Response(
     *         response=10003,
     *         description="UPDATE_FAIL",
     *         @JsonContent(ref="#/components/schemas/error")
     *     )
     * )
     */
    public function edit(array $params)
    {
        if (!isset($params['id']) || $params['id'] <= 0) {
            throw new BusinessException(ErrorCode::ID_REQUIRED);
        }

        $data = $this->_checkData($params);
        if (empty($data)) {
            return $this->success();
        }

        $result = NotifyTemplateConfig::where('id', $params['id'])->update($data);
        if (!$result)
            throw new BusinessException(ErrorCode::UPDATE_FAIL);
        return $this->success();
    }

    /**
     * 校验数据
     * 
     * @param array $params
     * @return $data
     */
    private function _checkData($params = [])
    {
        $data = [];
        if (isset($params['action_id']) && !empty($params['action_id'])) {
            $data['notify_code'] = $params['notify_code'];
        }
        if (isset($params['template_id']) && $params['template_id'] > 0) {
            $data['template_id'] = $params['template_id'];
        }
        if (isset($params['action_id']) && $params['action_id'] > 0) {
            $data['action_id'] = $params['action_id'];
        }
        return $data;
    }

    /**
     * @Post(
     *     path="/notify_template_config/delete",
     *     operationId="delete",
     *     tags={"NotifyTemplateConfigService"},
     *     summary="删除消息模版配置",
     *     description="根据主键ID删除消息模版配置（支持批量）",
     *     @Parameter(ref="#/components/parameters/ids"),
     *     @Response(
     *         response=200,
     *         description="操作成功",
     *         @JsonContent(ref="#/components/schemas/success")
     *     ),
     *     @Response(
     *         response=10004,
     *         description="DELETE_FAIL",
     *         @JsonContent(ref="#/components/schemas/error")
     *     )
     * )
     */
    public function delete(string $ids)
    {
        if (!$ids) {
            throw new BusinessException(ErrorCode::IDS_REQUIRED);
        }

        $idsArr = explode(',', $ids);
        $result = NotifyTemplateConfig::whereIn('id', $idsArr)->delete();
        if (!$result) {
            throw new BusinessException(ErrorCode::DELETE_FAIL);
        }

        return $this->success();
    }
}
