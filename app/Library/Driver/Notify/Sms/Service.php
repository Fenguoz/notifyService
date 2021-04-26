<?php

namespace Driver\Notify\Sms;

use Driver\Notify\AbstractService;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\PhoneNumber;

class Service extends AbstractService
{
    protected $key;
    protected $url;
    protected $phoneNumber;
    protected $area;

    public function __construct($param)
    {
        $this->param = $param;
        $this->phoneNumber = $this->param['phone_number'];
        $this->area = isset($this->param['area']) ? $this->param['area'] : '86';
        unset($this->param['phone_number']);
        unset($this->param['area']);
    }

    public function send()
    {
        $sendData = [];
        $sendData['template'] = $this->template->code;

        if (isset($this->param['content']) && !empty($this->param['content'])) {
            $sendData['content'] = $this->param['content'];
        } else {
            $sendData['content'] = $this->replaceTemplate();
        }
        $sendData['data'] = $this->param;
        try {
            $easySms = new EasySms($this->config);
            $phoneNumber = new PhoneNumber($this->phoneNumber, $this->area);
            $content = $easySms->send($phoneNumber, $sendData);
            // //Success {"juhe":{"gateway":"juhe","status":"success","result":{"reason":"\u64cd\u4f5c\u6210\u529f","result":{"sid":"1721120152805315800","fee":1,"count":1},"error_code":0}}}
        } catch (NoGatewayAvailableException $e) {
            return $this->error($e->getLastException()->getCode(), $e->getLastException()->getMessage());
        }

        if ($content) {
            foreach ($content as $gateways => $info) {
                if ($info['status'] != 'success')
                    return $this->error($info['result']['error_code'], $gateways . ":" . $info['result']['reason']);
            }
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

    public function replaceTemplate()
    {
        $content = $this->template->content;
        if ($content && $this->templateValue) {
            $key = [];
            $value = [];
            foreach ($this->templateValue as $k => $v) {
                $key[] = $k;
                $value[] = $v;
            }
            $content = str_replace($key, $value, $content);
        }
        return $content;
    }
}
