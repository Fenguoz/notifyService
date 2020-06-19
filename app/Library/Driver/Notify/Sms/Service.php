<?php

namespace Driver\Notify\Sms;

use Driver\Notify\AbstractService;
use Driver\Notify\HttpUtils;

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
        $params = array(
            'key'   => $this->config['key'],
            'mobile'    => $this->param['phone_number'],
            'tpl_id'    => $this->template->code,
            'tpl_value' => http_build_query($this->templateValue)
        );

        $content = HttpUtils::http($this->config['url'], $params, $this->config['method']);
        // Error  {"reason":"模板变量不符合规范","result":NULL,"error_code":205404}
        // Success  {"reason":"操作成功","result":{"sid":"1720612132040477500","fee":1,"count":1},"error_code":0}
        if ($content) {
            $result = json_decode($content, true);
            if ($result['error_code'] != 0) $this->error($result['error_code'], $result['reason']);
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
