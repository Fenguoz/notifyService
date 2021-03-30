<?php
/**
 * @OA\Schema(
 *      schema="notify_template",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="code", description="状态", type="integer", default="200"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  description="数据",
 *                  @OA\Items(ref="#/components/schemas/notify_template_model"),
 *              ),
 *          )
 *      }
 *  )
 *
 */
/**
 * @OA\Schema(
 *      schema="notify_template_model",
 *      @OA\Property(property="id", description="主键ID", type="integer", default="1"),
 *      @OA\Property(property="name", description="名称", type="string", default=""),
 *      @OA\Property(property="code", description="第三方标识码", type="string", default=""),
 *      @OA\Property(property="content", description="内容", type="string", default=""),
 *      @OA\Property(property="param", description="模板变量", type="string", default=""),
 *      @OA\Property(property="created_at", description="创建时间", type="string", default=""),
 *  )
 */
