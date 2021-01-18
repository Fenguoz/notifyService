<?php

namespace Driver\Notify\Email;

use Driver\Notify\AbstractService;
use Driver\Notify\HttpUtils;

class Service extends AbstractService
{
    protected $emails;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function send()
    {
    }

    public function sendBatch()
    {
        $params = [
            'apiUser' => $this->config['user'],
            'apiKey' => $this->config['key'],
            'from' => $this->config['from'],
            'templateInvokeName' => $this->template->code,
            'xsmtpapi' => json_encode([
                'to' => $this->account,
                'sub' => $this->rebuildBatchTemplateValue(),
            ]),
        ];

        $content = HttpUtils::http($this->config['url'], $params, $this->config['method']);
        //Error {"result":false,"statusCode":40813,"message":"xsmtpapi格式错误","info":{}}
        //Success {"result":true,"statusCode":200,"message":"请求成功","info":{"emailIdList":["1591957142335_129472_18860_3372.sc-10_9_13_213-inbound0$243944672@qq.com"]}}
        if ($content) {
            $result = json_decode($content, true);
            if (!$result['result'])
                return $this->error($result['statusCode'], $result['message']);
        } else {
            return $this->error(500, 'NETWORK_ERROR');
        }

        return $this->_notify();
    }

    public function rebuildBatchTemplateValue()
    {
        $tpl = [];
        foreach ($this->templateValue as $param) {
            foreach ($param as $replace_str => $variable) {
                $tpl[$replace_str][] = $variable;
            }
        }
        return $tpl;
    }

    public function _return()
    {
    }
}
