<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\NotifyTemplate;
use App\Rpc\NotifyTemplateServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Response;

/**
 * @RpcService(name="NotifyTemplateService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyTemplateService extends BaseService implements NotifyTemplateServiceInterface
{
    /**
     * @Post(
     *     path="/notify_template/getList",
     *     operationId="getList",
     *     tags={"NotifyTemplateService"},
     *     summary="消息模版列表",
     *     description="消息模版列表",
     *     @Parameter(ref="#/components/parameters/where_obj"),
     *     @Parameter(ref="#/components/parameters/order_obj"),
     *     @Parameter(ref="#/components/parameters/count"),
     *     @Parameter(ref="#/components/parameters/page"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/notify_template")
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
        $list = (new NotifyTemplate())->getList($where, $order, $count, $page);
        if (!$list) {
            throw new BusinessException(ErrorCode::NO_DATA);
        }

        return $this->success($list);
    }

    /**
     * @Post(
     *     path="/notify_template/getPagesInfo",
     *     operationId="getPagesInfo",
     *     tags={"NotifyTemplateService"},
     *     summary="消息模版分页信息",
     *     description="消息模版分页信息",
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
        $pageInfo = (new NotifyTemplate)->getPagesInfo($where, $count, $page);
        return $this->success($pageInfo);
    }

    /**
     * @Post(
     *     path="/notify_template/add",
     *     operationId="add",
     *     tags={"NotifyTemplateService"},
     *     summary="消息模版文章",
     *     description="消息模版文章",
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

        $data = $this->_checkData($params);
        $result = NotifyTemplate::create($data);
        if (!$result)
            throw new BusinessException(ErrorCode::ADD_ERROR);
        return $this->success();
    }

    /**
     * @Post(
     *     path="/notify_template/edit",
     *     operationId="edit",
     *     tags={"NotifyTemplateService"},
     *     summary="编辑消息模版",
     *     description="编辑消息模版",
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
        $result = NotifyTemplate::where('id', $params['id'])->update($data);
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
        if (isset($params['code']) && $params['code']) {
            $data['code'] = $params['code'];
        }
        if (isset($params['content']) && $params['content']) {
            $data['content'] = $params['content'];
        }
        if (isset($params['param']) && $params['param']) {
            $data['param'] = $params['param'];
        }
        return $data;
    }

    /**
     * @Post(
     *     path="/notify_template/delete",
     *     operationId="delete",
     *     tags={"NotifyTemplateService"},
     *     summary="删除消息模版",
     *     description="根据主键ID删除消息模版（支持批量）",
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
        $result = NotifyTemplate::whereIn('id', $idsArr)->delete();
        if (!$result) {
            throw new BusinessException(ErrorCode::DELETE_FAIL);
        }

        return $this->success();
    }
}
