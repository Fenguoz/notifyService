<?php
/**
 * @OA\Schema(
 *      schema="notify_template_config",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="code", description="状态", type="integer", default="200"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  description="数据",
 *                  @OA\Items(ref="#/components/schemas/notify_template_config_model"),
 *              ),
 *          )
 *      }
 *  )
 *
 */
/**
 * @OA\Schema(
 *      schema="notify_template_config_model",
 *      @OA\Property(property="id", description="主键ID", type="integer", default="1"),
 *      @OA\Property(property="notify_code", description="标识码", type="string", default=""),
 *      @OA\Property(property="template_id", description="模版ID", type="integer", default=""),
 *      @OA\Property(property="action_id", description="行为动作ID", type="integer", default=""),
 *      @OA\Property(property="created_at", description="创建时间", type="string", default=""),
 *  )
 */
