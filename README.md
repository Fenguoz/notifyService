<h1 align="center">通知服务</h1>

## 概述

通知服务是微服务就是一个独立的实体，它可以独立被部署，也可以作为一个操作系统进程存在。服务与服务之间存在隔离性，服务之间均通过网络调用进行通信，从而加强服务之间的隔离性，避免紧耦合。

该服务基于 Hyperf2.0 框架实现。通过 JSON RPC 轻量级的 RPC 协议标准，可自定义基于 HTTP 协议来传输，或直接基于 TCP 协议来传输方式实现服务注册。客户端只需要通过协议标准直接调用已封装好的方法即可。

适用范围：短信、邮箱、微信、APP消息推送、消息队列

## 环境要求

- PHP >= 7.2
- Swoole PHP 扩展 >= 4.5，并关闭了 `Short Name`
- Mysql 5.7
- Nginx
- Consul

## 特点

1. 支持多种通知方式
1. 一套写法兼容所有平台
1. 配置即可灵活增减

### 支持发送渠道

- 邮箱
- 短信
- 微信
- APP消息推送
- 自定义

### 支持方法

#### 消息 (/notify/)
- 单条发送 `send(int $code, string $action, array $params)`
- 批量发送 `sendBatch(int $code, string $action, array $params)`
- 异步消息队列 `queue(string $action, array $params, int $sort)`

#### 消息配置 (/notify_config/)
- 消息配置列表 `getList(array $where, array $order, int $count, int $page)`
- 消息配置分页信息 `getPagesInfo(array $where, int $count, int $page)`
- 添加消息配置 `add(array $params)`
- 编辑消息配置 `edit(array $params)`
- 删除消息配置 `delete(string $code)`

#### 消息模版 (/notify_template/)
- 消息模版列表 `getList(array $where, array $order, int $count, int $page)`
- 消息模版分页信息 `getPagesInfo(array $where, int $count, int $page)`
- 添加消息模版 `add(array $params)`
- 编辑消息模版 `edit(array $params)`
- 删除消息模版 `delete(string $code)`

#### 消息模版配置 (/notify_template_config/)
- 消息模版配置列表 `getList(array $where, array $order, int $count, int $page)`
- 消息模版配置分页信息 `getPagesInfo(array $where, int $count, int $page)`
- 添加消息模版配置 `add(array $params)`
- 编辑消息模版配置 `edit(array $params)`
- 删除消息模版配置 `delete(string $code)`

#### 动作行为 (/notify_action/)
- 动作行为列表 `getList(array $where, array $order, int $count, int $page)`
- 动作行为分页信息 `getPagesInfo(array $where, int $count, int $page)`
- 添加动作行为 `add(array $params)`
- 编辑动作行为 `edit(array $params)`
- 删除动作行为 `delete(string $code)`

## 快速开始

### 部署

``` bash
# 克隆项目
git clone ...

# 进入根目录
cd {file}

# 安装扩展包
composer install

# 配置env文件
cp .env.example .env

# 数据迁移
php bin/hyperf.php migrate --seed

# 运行 Consul（若已运行，忽略）
consul agent -dev -bind 127.0.0.1

# 运行
php bin/hyperf.php start
```

### 接口调用

查看 **可调用接口** 详情，请查看 Swagger 文档。

访问路径：public/swagger/index.html

swagger文件：public/swagger/openapi.json

Hyperf 调用示例：
``` php
use Hyperf\Di\Annotation\Inject;
use App\Rpc\NotifyServiceInterface;
use App\Constants\Notify\Notify;

class Xxx
{
    /**
     * @Inject
     * @var NotifyServiceInterface
     */
    protected $notifyService;

    public function execute()
    {
        $ret = $this->notifyService->send(Notify::Sms, 'user.register', [
            'phone_number' => '18888888888',
            'code' => '888888',
        ]);
    }
}

// 常量 Notify.php
namespace App\Constants\Notify;

final class Notify
{
    const null = 0;
    const Sms = 1;
    const Email = 2;
    const Message = 3;
    const Wechat = 4;
    static public $__names = array(
        0 => null,
        1 => 'Sms',
        2 => 'Email',
        3 => 'GeTui',
        4 => 'Wechat',
    );
}

```

PHP 调用示例：
``` php
use GuzzleHttp\Client;

$host = 'http://127.0.0.1:9801';
(new Client)->post($host, [
    'json' => [
        'jsonrpc' => '2.0',
        'method' => '/notify/send',
        'params' => [1, 'user.register', ['code' => '888888', 'phone_number' => '18888888888']],
        'id' => 1,
    ]
]);
```

CURL 调用示例：
``` bash
curl -X POST -H "Content-Type: application/json" \
--data '{"jsonrpc":"2.0","method":"/notify/send","params":[1, "method":"/notify/send", "params":[1, "user.register", ["code" => "888888", "phone_number" => "18888888888"]],"id":1}' \
http://127.0.0.1:9801
```

## 扩展包

| 扩展包名 | 描述 | 应用场景 |
| :-----| :---- | :---- |
| [yurunsoft/phpmailer-swoole](https://github.com/Yurunsoft/PHPMailer-Swoole) | Swoole 协程环境下的可用的 PHPMailer | 发送邮箱通知 |
| [overtrue/easy-sms](https://github.com/overtrue/easy-sms) | 一款满足你的多种发送需求的短信发送组件 | 发送短信通知 |
| [overtrue/wechat](https://github.com/w7corp/easywechat) | 世界上最好的微信开发SDK | 发送微信通知 |
| [getuilaboratory/getui-pushapi-php-client](https://github.com/GetuiLaboratory/getui-pushapi-php-client) | APP应用消息推送SDK | APP站内推送通知 |

## 脚本命令
| 命令行 | 说明 | crontab |
| :-----| :---- | :---- |
| composer install | 安装扩展包 | -- |
| php bin/hyperf.php swagger:gen -o ./public/swagger/ -f json | 生成swagger文档 | -- |