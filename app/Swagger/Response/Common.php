<?php
/**
 * @OA\Schema(
 *      schema="success",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              required={"id"},
 *              @OA\Property(property="code", description="状态", type="integer", default="1"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
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
 *          property="code",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="message",
 *          type="string"
 *      )
 *  )
 */

/**
 *  @OA\Schema(
 *      schema="data",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="code", description="状态", type="integer", default="200"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
 *              @OA\Property(property="data", description="数据", type="string", default="")
 *          )
 *      }
 *  )
 */

/**
 *  @OA\Schema(
 *      schema="pageInfo",
 *      type="object",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(property="code", description="状态", type="integer", default="200"),
 *              @OA\Property(property="message", description="信息", type="string", default="操作成功"),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  allOf={
 *                       @OA\Schema(
 *                          @OA\Property(property="page", description="当前页", type="integer", default="1"),
 *                          @OA\Property(property="count", description="每页条数", type="integer", default="10"),
 *                          @OA\Property(property="total", description="总共条数", type="integer", default="0"),
 *                          @OA\Property(property="total_page", description="总共页数", type="integer", default="0"),
 *                      )
 *                  }
 *              )
 *          )
 *      }
 *  )
 */

 /**
 * @OA\Schema(
 *      schema="no_data",
 *      @OA\Property(
 *          property="code",
 *          type="integer",
 *          format="int32",
 *          default="10005"
 *      ),
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          default="NO_DATA"
 *      )
 *  )
 */