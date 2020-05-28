<?php

// 请求参数
/**
 * 
 * @OA\Parameter(
 *      parameter="target_id",
 *      name="target_id",
 *      description="目标的ID(比如文章ID)",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *      )
 * )
 * 
 * @OA\Parameter(
 *      parameter="subscription_type_id",
 *      name="type_id",
 *      description="订阅类型ID",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *      )
 * )
 * 
 * @OA\Parameter(
 *      parameter="subscription_type",
 *      name="type",
 *      description="订阅类型(默认event,topic)",
 *      in="query",
 *      required=false,
 *      @OA\Schema(
 *          type="string",
 *      )
 * )
 * 
 * @OA\Parameter(
 *      parameter="receiver_id",
 *      name="receiver_id",
 *      description="接收者ID",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *      )
 * )
 * 
 * @OA\Parameter(
 *      parameter="receiver_type",
 *      name="receiver_type",
 *      description="接收者类型(默认user,admin)",
 *      in="query",
 *      required=false,
 *      @OA\Schema(
 *          type="string",
 *      )
 * )
 * 
 * @OA\Parameter(
 *      parameter="template_param",
 *      name="template_param",
 *      description="扩展参数(json字符串)",
 *      in="query",
 *      required=false,
 *      @OA\Schema(
 *          type="string",
 *      )
 * )
 * 
 * @OA\Parameter(
 *      parameter="client_id",
 *      name="client_id",
 *      description="App客户端ID",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="string",
 *      )
 * )
 */
