<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\NotifyConfig;
use App\Rpc\NotifyConfigServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Response;

/**
 * @RpcService(name="NotifyConfigService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyConfigService extends BaseService implements NotifyConfigServiceInterface
{
    /**
     * @Post(
     *     path="/notify_config/getList",
     *     operationId="getList",
     *     tags={"NotifyConfigService"},
     *     summary="消息配置列表",
     *     description="消息配置列表",
     *     @Parameter(ref="#/components/parameters/where_obj"),
     *     @Parameter(ref="#/components/parameters/order_obj"),
     *     @Parameter(ref="#/components/parameters/count"),
     *     @Parameter(ref="#/components/parameters/page"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/notify_config")
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
        array $order = ['sort' => 'ASC'],
        int $count = 0,
        int $page = 1
    ) {
        $list = (new NotifyConfig)->getList($where, $order, $count, $page);
        if (!$list) {
            throw new BusinessException(ErrorCode::NO_DATA);
        }

        return $this->success($list);
    }

    /**
     * @Post(
     *     path="/notify_config/getPagesInfo",
     *     operationId="getPagesInfo",
     *     tags={"NotifyConfigService"},
     *     summary="消息配置分页信息",
     *     description="消息配置分页信息",
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
        $pageInfo = (new NotifyConfig)->getPagesInfo($where, $count, $page);
        return $this->success($pageInfo);
    }

    /**
     * @Post(
     *     path="/notify_config/add",
     *     operationId="add",
     *     tags={"NotifyConfigService"},
     *     summary="添加消息配置",
     *     description="添加消息配置",
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
        if (!isset($params['name']) || empty($params['name'])) {
            throw new BusinessException(ErrorCode::NAME_REQUIRED);
        }
        if (!isset($params['config']) || empty($params['config'])) {
            throw new BusinessException(ErrorCode::CONFIG_REQUIRED);
        }

        $data = $this->_checkData($params);
        if (!isset($params['code']) || empty($params['code'])) {
            throw new BusinessException(ErrorCode::CODE_REQUIRED);
        }
        $data['code'] = $params['code'];

        $isExist = NotifyConfig::isExistCode($data['code']);
        if ($isExist) {
            throw new BusinessException(ErrorCode::CODE_EXIST);
        }

        $result = NotifyConfig::create($data);
        if (!$result)
            throw new BusinessException(ErrorCode::ADD_ERROR);
        return $this->success();
    }

    /**
     * @Post(
     *     path="/notify_config/edit",
     *     operationId="edit",
     *     tags={"NotifyConfigService"},
     *     summary="编辑消息配置",
     *     description="编辑消息配置",
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
        if (!isset($params['code']) || empty($params['code'])) {
            throw new BusinessException(ErrorCode::CODE_REQUIRED);
        }

        $data = $this->_checkData($params);
        $result = NotifyConfig::where('code', $params['code'])->update($data);
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
        if (isset($params['name']) && $params['name']) {
            $data['name'] = $params['name'];
        }
        if (isset($params['config']) && $params['config']) {
            $data['config'] = $params['config'];
        }
        if (isset($params['desc']) && $params['desc']) {
            $data['desc'] = $params['desc'];
        }
        if (isset($params['status']) && $params['status']) {
            $data['status'] = $params['status'];
        }
        if (isset($params['sort']) && $params['sort']) {
            $data['sort'] = $params['sort'];
        }
        return $data;
    }

    /**
     * @Post(
     *     path="/notify_config/delete",
     *     operationId="delete",
     *     tags={"NotifyConfigService"},
     *     summary="删除消息配置",
     *     description="根据标识码code删除消息配置",
     *     @Parameter(ref="#/components/parameters/code"),
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
    public function delete(string $code)
    {
        if (!$code) {
            throw new BusinessException(ErrorCode::CODE_REQUIRED);
        }

        $result = NotifyConfig::where('code', $code)->delete();
        if (!$result) {
            throw new BusinessException(ErrorCode::DELETE_FAIL);
        }

        return $this->success();
    }
}
