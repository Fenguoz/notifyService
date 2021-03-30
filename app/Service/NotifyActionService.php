<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\NotifyAction;
use App\Rpc\NotifyActionServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Response;

/**
 * @RpcService(name="NotifyActionService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyActionService extends BaseService implements NotifyActionServiceInterface
{
    /**
     * @Post(
     *     path="/notify_action/getList",
     *     operationId="getList",
     *     tags={"NotifyActionService"},
     *     summary="动作行为列表",
     *     description="动作行为列表",
     *     @Parameter(ref="#/components/parameters/where_obj"),
     *     @Parameter(ref="#/components/parameters/order_obj"),
     *     @Parameter(ref="#/components/parameters/count"),
     *     @Parameter(ref="#/components/parameters/page"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/notify_action")
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
        $list = (new NotifyAction)->getList($where, $order, $count, $page);
        if (!$list) {
            throw new BusinessException(ErrorCode::NO_DATA);
        }

        return $this->success($list);
    }

    /**
     * @Post(
     *     path="/notify_action/getPagesInfo",
     *     operationId="getPagesInfo",
     *     tags={"NotifyActionService"},
     *     summary="动作行为分页信息",
     *     description="动作行为分页信息",
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
        $pageInfo = (new NotifyAction)->getPagesInfo($where, $count, $page);
        return $this->success($pageInfo);
    }

    /**
     * @Post(
     *     path="/notify_action/add",
     *     operationId="add",
     *     tags={"NotifyActionService"},
     *     summary="动作行为文章",
     *     description="动作行为文章",
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
        if (!isset($params['module']) || empty($params['module'])) {
            throw new BusinessException(ErrorCode::MODULE_REQUIRED);
        }

        $data = $this->_checkData($params);
        $result = NotifyAction::create($data);
        if (!$result) {
            throw new BusinessException(ErrorCode::ADD_ERROR);
        }
        return $this->success();
    }

    /**
     * @Post(
     *     path="/notify_action/edit",
     *     operationId="edit",
     *     tags={"NotifyActionService"},
     *     summary="编辑动作行为",
     *     description="编辑动作行为",
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
        $result = NotifyAction::where('id', $params['id'])->update($data);
        if (!$result) {
            throw new BusinessException(ErrorCode::UPDATE_FAIL);
        }
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
        if (isset($params['module']) && $params['module']) {
            $data['module'] = $params['module'];
        }
        if (isset($params['parent_id'])) {
            $data['parent_id'] = $params['parent_id'];
        }
        if (isset($params['action']) && $params['action']) {
            $data['action'] = $params['action'];
        }
        return $data;
    }

    /**
     * @Post(
     *     path="/notify_action/delete",
     *     operationId="delete",
     *     tags={"NotifyActionService"},
     *     summary="删除动作行为",
     *     description="根据主键ID删除动作行为（支持批量）",
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
        $result = NotifyAction::whereIn('id', $idsArr)->delete();
        if (!$result) {
            throw new BusinessException(ErrorCode::DELETE_FAIL);
        }

        return $this->success();
    }
}
