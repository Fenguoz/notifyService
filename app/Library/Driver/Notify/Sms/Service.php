<?php

namespace Driver\Notify\Sms;

use Driver\Notify\AbstractService;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\PhoneNumber;

class Service extends AbstractService
{
    protected $template;
    protected $data;
    protected $content;

    public function __construct(array $param)
    {
        if (empty($param)) {
            return $this->error(500, 'Parameter cannot be empty');
        }
        if (!isset($param['data']) || empty($param['data'])) {
            return $this->error(500, 'Data cannot be empty');
        }
        $this->template = isset($param['template']) ? $param['template'] : '';
        $this->content = isset($param['content']) ? $param['content'] : '';
        $this->data = $param['data'];
    }

    public function send()
    {
        if (!isset($this->data['phone_number'])) {
            return $this->error(500, 'Phone number cannot be empty');
        }

        $area = isset($this->data['area']) ? $this->data['area'] : 86;
        $phoneNumber = new PhoneNumber($this->data['phone_number'], $area);
        unset($this->data['phone_number']);

        $sendData = [];
        $sendData['template'] = $this->template;
        $sendData['content'] = $this->content;
        $sendData['data'] = $this->data;

        try {
            $easySms = new EasySms($this->config);
            $content = $easySms->send($phoneNumber, $sendData);
            // //Success {"juhe":{"gateway":"juhe","status":"success","result":{"reason":"\u64cd\u4f5c\u6210\u529f","result":{"sid":"1721120152805315800","fee":1,"count":1},"error_code":0}}}
        } catch (NoGatewayAvailableException $e) {
            return $this->error($e->getLastException()->getCode(), $e->getLastException()->getMessage());
        }

        if ($content) {
            foreach ($content as $gateways => $info) {
                if ($info['status'] != 'success') {
                    return $this->error($info['result']['error_code'], $gateways . ":" . $info['result']['reason']);
                }
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
}
