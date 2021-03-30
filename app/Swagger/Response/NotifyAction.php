<?php
/**
 * @OA\Schema(
 *      schema="notify_action",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="code", description="状态", type="integer", default="200"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  description="数据",
 *                  @OA\Items(ref="#/components/schemas/notify_action_model"),
 *              ),
 *          )
 *      }
 *  )
 *
 */
/**
 * @OA\Schema(
 *      schema="notify_action_model",
 *      @OA\Property(property="id", description="主键ID", type="integer", default="1"),
 *      @OA\Property(property="name", description="名称", type="string", default=""),
 *      @OA\Property(property="parent_id", description="父ID", type="integer", default="0"),
 *      @OA\Property(property="module", description="模块", type="string", default=""),
 *      @OA\Property(property="action", description="行为标识", type="string", default=""),
 *      @OA\Property(property="created_at", description="创建时间", type="string", default=""),
 *  )
 */
