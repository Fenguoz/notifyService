<?php
/**
 * @OA\Schema(
 *      schema="success",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              required={"id"},
 *              @OA\Property(property="status", description="状态", type="integer", default="1"),
 *              @OA\Property(property="msg", description="信息", type="string", default="操作成功"),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  allOf={}
 *              )
 *          )
 *      }
 *  )
 */

/**
 * @OA\Schema(
 *      schema="error",
 *      @OA\Property(
 *          property="status",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="msg",
 *          type="string"
 *      )
 *  )
 */
