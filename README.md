<h1 align="center">NotifyService</h1>

<p align="center">基于 HyperF 框架开发的消息服务</p>

<p align="center">
  <a href="https://hyperf.io"><img src="https://img.shields.io/badge/hyperf-1.1-brightgreen" alt="Hyperf Version"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/php-%3E=7.1-brightgreen" alt="Php Version"></a>
  <a href="https://github.com/swoole/swoole-src"><img src="https://img.shields.io/badge/swoole-%3E=4.5-brightgreen" alt="Swoole Version"></a>
</p>

## 特点

1. 支持多种通知方式
1. 一套写法兼容所有平台
1. 配置即可灵活增减

## 支持方式

- 单条发送
- 批量发送
- 异步队列

## 支持驱动

- Mail : [yurunsoft/phpmailer-swoole](https://github.com/Yurunsoft/PHPMailer-Swoole)
- SMS : [overtrue/easy-sms](https://github.com/overtrue/easy-sms)
- Wechat : [overtrue/wechat](https://github.com/w7corp/easywechat)
- GeTui : [getuilaboratory/getui-pushapi-php-client](https://github.com/GetuiLaboratory/getui-pushapi-php-client)