<h1 align="center">通知服务</h1>

## 概述

----

通知服务是微服务就是一个独立的实体，它可以独立被部署，也可以作为一个操作系统进程存在。服务与服务之间存在隔离性，服务之间均通过网络调用进行通信，从而加强服务之间的隔离性，避免紧耦合。

该服务基于 Hyperf2.0 框架实现。通过 JSON RPC 轻量级的 RPC 协议标准，可自定义基于 HTTP 协议来传输，或直接基于 TCP 协议来传输方式实现服务注册。客户端只需要通过协议标准直接调用已封装好的方法即可。

适用范围：短信、邮箱、微信、APP消息推送

## 环境要求

----

- PHP >= 7.2
- Swoole PHP 扩展 >= 4.5，并关闭了 `Short Name`
- Nginx
- Consul

## 特点

----

1. 支持多种通知方式
1. 一套写法兼容所有平台
1. 配置即可灵活增减

### 支持发送渠道

- 短信 `Sms`
- 邮箱 `Mail`
- 微信 `Wechat`
- APP消息推送 `App`
- 自定义

### 支持方法

#### 消息 (/notify/)
- 单条发送 `send(int $code, string $action, array $params)`
- 批量发送 `sendBatch(int $code, string $action, array $params)`

## 快速开始

----

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

# 运行 Consul（若已运行，忽略）
consul agent -dev -bind 127.0.0.1

# 运行
php bin/hyperf.php start
```

### 各驱动配置说明
短信

``` php
$notifyDriver = 'Sms';
$config = [
    'default' => [
        'gateways' => ['moduyun']
    ],
    'gateways' => [
        'moduyun' => [
            'signId' => '****',
            'accesskey' => '****',
            'secretkey' => '****']
        ]
    ];

// 内容使用 content + data 或 template + data
$params = [
    'template' => '****',                 // 选填 模版ID
    'content' => '您的验证码是888888....',  // 选填 内容
    'data' => [
        'phone_number' => '18888888888',  // 必填 手机号
        'area' => '18888888888',          // 选填 手机区号 默认:86
        'code' => 888888,                 // 选填 模版参数
        // ....,                          // 选填 模版参数
    ]
];
```

#### 邮箱

``` php
$notifyDriver = 'Mail';
$config = [
    'host' => 'smtp.live.com',
    'port' => 587,
    'smtp_secure' => 'ssl',
    'username' => '',
    'password' => '',
    'send_mail' => '',
    'send_nickname' => '',
    'attachment' => ''
];

// 内容使用 content + data
$params = [
    'content' => '尊敬的xx用户：....',  // 必填 内容
    'data' => [
        'subject' => '邮箱验证码'       // 必填 副标题
        'address' => 'xx@qq.com'      // 必填 副标题
    ]
];
```

#### App
``` php
$notifyDriver = 'App';
$config = [
    'app_id' => 'xxx',
    'app_key' => 'xxx',
    'app_secret' => 'xxx',
    'master_secret' => 'xxx'
];

// 内容使用 content + data
$params = [
    'content' => '恭喜成功注册成为xxx平台会员！', // 必填 内容
    'data' => [
        'title' => '注册成功通知',              // 必填 标题
        'client_id' => 'xxx'                  // 必填 客户端ID
    ]
];
```

#### 微信
``` php
$notifyDriver = 'Wechat';
$config = [
    'officialAccount' => [
        'app_id' => 'xxx',
        'secret' => 'xxx',
        'response_type' => 'array'
    ],
    'miniProgram' => [
        'app_id' => 'xxx',
        'secret' => 'xxx',
        'response_type' => 'array'
    ]
];

// 内容使用 template + data
$params = [
    'template' => 'xxx',            // 必填 公众号模版ID
    'data' => [
        'openid' => 'xxx',          // 必填 公众号OpenId
        'data' => [                 // 必填 模版数据
            'keyword1' => 'VALUE',
            'keyword2' => 'VALUE2',
            // ....,
        ],
        'url' => '',                // 选填 公众号跳转
        'miniprogram_path' => ''    // 选填 小程序跳转(优先级高于公众号)
    ]
];
```

### 接口调用
Hyperf 调用示例：
``` php
use Hyperf\Di\Annotation\Inject;
use App\Rpc\NotifyServiceInterface;

class Xxx
{
    /**
     * @Inject
     * @var NotifyServiceInterface
     */
    protected $notifyService;

    public function demo()
    {
        $ret = $this->notifyService->send($notifyDriver, $config, $params);
    }
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
        'params' => [$notifyDriver, $config, $params],
        'id' => 1,
    ]
]);
```

## 扩展包

----

| 扩展包名 | 描述 | 应用场景 |
| :-----| :---- | :---- |
| [yurunsoft/phpmailer-swoole](https://github.com/Yurunsoft/PHPMailer-Swoole) | Swoole 协程环境下的可用的 PHPMailer | 发送邮箱通知 |
| [overtrue/easy-sms](https://github.com/overtrue/easy-sms) | 一款满足你的多种发送需求的短信发送组件 | 发送短信通知 |
| [overtrue/wechat](https://github.com/w7corp/easywechat) | 世界上最好的微信开发SDK | 发送微信通知 |
| [getuilaboratory/getui-pushapi-php-client](https://github.com/GetuiLaboratory/getui-pushapi-php-client) | APP应用消息推送SDK | APP站内推送通知 |

## 脚本命令

----

| 命令行 | 说明 | crontab |
| :-----| :---- | :---- |
| composer install | 安装扩展包 | -- |