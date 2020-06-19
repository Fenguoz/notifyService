# 项目Api基础框架

#### 环境配置：

- PHP >= 7.2
- Swoole PHP 扩展 >= 4.4，并关闭了 `Short Name`
- OpenSSL PHP 扩展
- JSON PHP 扩展
- PDO PHP 扩展
- Redis PHP 扩展
- Protobuf PHP 扩展
- Mysql5.7
- Nginx
- Swagger3.0
- RabbitMQ
- Consul

# Swagger

- 访问地址：Nginx自行配置（地址：public/swagger/index.html）
- 更新命令：php bin/hyperf.php swagger:gen -o ./public/swagger/ -f json

#### Swagger编写规范

- 初始项目：先BaseController中修改基本配置（项目名称及域名）
- 请求方式（Post|Get|Put|Delete）
- 是否需要会员认证（security={{"Authorization":{}}}）
- 接口描述
- 传参
    - 是否必填
    - 注明备注
    - 参数配置项（路径：/app/Swagger/Parameters 根据模块进行划分文件）
- 返参
    - 注明备注
    - 参数配置项（路径：/app/Swagger/Response 根据模块进行划分文件）

# PHP规范

#### Controller

- 命名规范
    - 根据模块划分Controller
    - 命名 = 模块 + Controller.php 大驼峰命名规则
- 禁止在Controller中直接调取Model，须通过Service调取Model
- 接口返参统一使用success方法（status=200成功，否则失败）
- Controller使命
    - 简单的参数校验
    - 一个或多个Service中方法调用
    - 返回接口结果
    
#### Service

- 命名规范
    - 根据模块划分Service
    - 命名 = 模块 + Service.php 大驼峰命名规则
- 一个方法不得超过100行代码，除特殊情况外需拆解成几个方法
- 错误码
    - 统一调用ErrorCode
    - 定义错误码根据模块从1-9划分
    - 错误码依次自增添加
    - 错误码配置（路径：/app/Exceptions/ErrorCode.php）
- Service使命
    - 参数校验及过滤
    - 高可用、低藕性
    - 处理业务逻辑
    
#### Model

- 命名规范
    - 命名 = 模块 + Model.php 大驼峰命名规则
- Model使命
    - 对应数据库表
    - 封装高可用数据
