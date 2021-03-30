<?php
/**
 * @OA\Schema(
 *      schema="notify_config",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="code", description="状态", type="integer", default="200"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  description="数据",
 *                  @OA\Items(ref="#/components/schemas/notify_config_model"),
 *              ),
 *          )
 *      }
 *  )
 *
 */
/**
 * @OA\Schema(
 *      schema="notify_config_model",
 *      @OA\Property(property="code", description="标识码", type="string", default=""),
 *      @OA\Property(property="name", description="名称", type="string", default=""),
 *      @OA\Property(property="desc", description="描述", type="string", default=""),
 *      @OA\Property(property="config", description="配置", type="string", default=""),
 *      @OA\Property(property="status", description="状态 1开启 0关闭", type="integer", default="1"),
 *      @OA\Property(property="sort", description="排序", type="integer", default="100"),
 *      @OA\Property(property="created_at", description="创建时间", type="string", default=""),
 *  )
 */
