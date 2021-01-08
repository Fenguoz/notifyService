<?php

namespace Driver\Notify\Sms;

use Driver\Notify\AbstractService;
use Overtrue\EasySms\EasySms;

class Service extends AbstractService
{
    protected $key;
    protected $url;
    protected $phoneNumber;

    public function __construct($param)
    {
        $this->param = $param;
        $this->phoneNumber = $this->param['phone_number'];
        unset($this->param['phone_number']);
    }

    public function send()
    {
        $sendData = [];
        $sendData['template'] = $this->template->code;
        if (isset($this->param['content']) && !empty($this->param['content'])) {
            $sendData['content'] = $this->param['content'];
            unset($this->param['content']);
        }
        $sendData['data'] = $this->param;
        $easySms = new EasySms($this->config);
        // // $number = new PhoneNumber(13188888888, 31);
        $content = $easySms->send($this->phoneNumber, $sendData);
        // //Success {"juhe":{"gateway":"juhe","status":"success","result":{"reason":"\u64cd\u4f5c\u6210\u529f","result":{"sid":"1721120152805315800","fee":1,"count":1},"error_code":0}}}

        if ($content) {
            foreach ($content as $gateways => $info) {
                if ($info['status'] != 'success') $this->error($info['result']['error_code'], $gateways . ":" . $info['result']['reason']);
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
