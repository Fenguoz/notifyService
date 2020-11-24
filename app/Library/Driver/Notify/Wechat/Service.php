<?php

namespace Driver\Notify\Wechat;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Driver\Notify\AbstractService;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;

class Service extends AbstractService
{
    protected $key;
    protected $url;
    protected $phone_number;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function send()
    {
        if (!isset($this->param['openid']) || empty($this->param['openid']))
            throw new BusinessException(
                ErrorCode::DATA_NOT_EXIST
            );
        if (!isset($this->param['data']) || empty($this->param['data']))
            throw new BusinessException(
                ErrorCode::DATA_NOT_EXIST
            );

        $app = Factory::officialAccount($this->config['officialAccount']);
        $handler = new CoroutineHandler();
        $config = $app['config']->get('http', []);
        $config['handler'] = HandlerStack::create($handler);
        $app->rebind('http_client', new Client($config));
        $app['guzzle_handler'] = $handler;

        $sendData = [];
        $sendData['template_id'] = $this->template->code;
        if ($this->param['openid']) $sendData['touser'] = $this->param['openid'];

        // 如果 url 和 miniprogram 字段都传，会优先跳转小程序。
        if (isset($this->param['url']) && !empty($this->param['url']))
            $sendData['url'] = $this->param['url'];
        // if (isset($this->param['miniprogram_path']) && !empty($this->param['miniprogram_path'])) {
        //     $sendData['miniprogram']['appid'] = $this->config['miniProgram']['app_id'];
        //     $sendData['miniprogram']['pagepath'] = $this->param['miniprogram_path'];
        // }

        // $sendData['data'] = [
        //     'keyword1' => 'VALUE',
        //     'keyword2' => 'VALUE2',
        // ];
        if ($this->param['data']) $sendData['data'] = $this->param['data'];

        $content = $app->template_message->send($sendData);
        // // Success  {"errcode":0,"errmsg":"ok","msgid":1619409037345800198}
        if ($content) {
            if ($content['errcode'] != 0){
                $this->error($content['errcode'], $content['errmsg']);
                throw new BusinessException(
                    $content['errcode'],
                    $content['errmsg']
                );
            }
        } else {
            $this->error(500, 'NETWORK_ERROR');
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
