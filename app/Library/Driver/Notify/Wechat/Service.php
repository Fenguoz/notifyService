<?php

namespace Driver\Notify\Wechat;

use App\Constants\ErrorCode;
use Driver\Notify\AbstractService;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;

class Service extends AbstractService
{
    protected $data;
    protected $template;
    public function __construct($param)
    {
        if (empty($param)) {
            return $this->error(500, 'Parameter cannot be empty');
        }
        if (!isset($param['data']) || empty($param['data'])) {
            return $this->error(500, 'data cannot be empty');
        }
        if (!isset($param['template']) || empty($param['template'])) {
            return $this->error(500, 'template cannot be empty');
        }
        $this->data = $param['data'];
        $this->template = $param['template'];
    }

    public function send()
    {
        if (!isset($this->data['openid']) || empty($this->data['openid']))
            return $this->error(ErrorCode::DATA_NOT_EXIST);

        if (!isset($this->data['data']) || empty($this->data['data']))
            return $this->error(ErrorCode::DATA_NOT_EXIST);

        $app = Factory::officialAccount($this->config['officialAccount']);
        $handler = new CoroutineHandler();
        $config = $app['config']->get('http', []);
        $config['handler'] = HandlerStack::create($handler);
        $app->rebind('http_client', new Client($config));
        $app['guzzle_handler'] = $handler;

        $sendData = [];
        $sendData['template_id'] = $this->template;
        if ($this->data['openid']) {
            $sendData['touser'] = $this->data['openid'];
        }

        // 如果 url 和 miniprogram 字段都传，会优先跳转小程序。
        if (isset($this->data['url']) && !empty($this->data['url'])) {
            $sendData['url'] = $this->data['url'];
        }
        if (isset($this->data['miniprogram_path']) && !empty($this->data['miniprogram_path'])) {
            $sendData['miniprogram']['appid'] = $this->config['miniProgram']['app_id'];
            $sendData['miniprogram']['pagepath'] = $this->data['miniprogram_path'];
        }

        // $sendData['data'] = [
        //     'keyword1' => 'VALUE',
        //     'keyword2' => 'VALUE2',
        // ];
        if ($this->data['data']) $sendData['data'] = $this->data['data'];

        $content = $app->template_message->send($sendData);
        // // Success  {"errcode":0,"errmsg":"ok","msgid":1619409037345800198}
        if ($content) {
            if ($content['errcode'] != 0)
                return $this->error($content['errcode'], $content['errmsg']);
        } else {
            return $this->error(500, 'NETWORK_ERROR');
        }
        return $this->_notify();
    }

    public function sendBatch()
    {
    }

    public function _return()
    {
    }
}
