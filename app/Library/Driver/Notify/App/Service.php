<?php

namespace Driver\Notify\App;

use Driver\Notify\AbstractService;

class Service extends AbstractService
{
    protected $data;
    protected $content;
    public function __construct($param)
    {
        if (empty($param)) {
            return $this->error(500, 'Parameter cannot be empty');
        }
        if (!isset($param['data']) || empty($param['data'])) {
            return $this->error(500, 'data cannot be empty');
        }
        if (!isset($param['content']) || empty($param['content'])) {
            return $this->error(500, 'content cannot be empty');
        }
        $this->data = $param['data'];
        $this->content = $param['content'];
    }

    public function send()
    {
        if (!isset($this->data['title'])) {
            return $this->error(500, 'title cannot be empty');
        }
        if (!isset($this->data['client_id'])) {
            return $this->error(500, 'client_id cannot be empty');
        }

        $igt = new \IGeTui('', $this->config['app_key'], $this->config['master_secret']);

        header("content-type:text/html;charset='utf-8'");
        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->IGtNotyPopLoadTemplate($this->data['title'], $this->content);

        //定义"SingleMessage"
        $message = new \IGtSingleMessage();

        $message->set_isOffline($this->config['is_offline']);
        $message->set_offlineExpireTime($this->config['offline_expire_time']);
        $message->set_data($template); //设置推送消息类型
        //接收方
        $target = new \IGtTarget();
        $target->set_appId($this->config['app_id']);
        $target->set_clientId($this->data['client_id']);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);
        } catch (\RequestException $e) {
            $requstId = $e->getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
        }
        var_dump($rep);
        //$rep success array(3) {"result":"ok","taskId":"OSS-0619_7e29d0aff873b088e4276412e231d29b","status":"successed_online"}
        return $this->_notify();
    }

    public function sendBatch()
    {
    }

    public function _return()
    {
    }

    private function IGtNotyPopLoadTemplate($title, $content)
    {
        $template =  new \IGtNotificationTemplate();
        $template->set_appId($this->config['app_id']);
        $template->set_appkey($this->config['app_key']);
        $template->set_transmissionType(1); //透传消息类型，Android平台控制点击消息后是否启动应用
        $template->set_transmissionContent('test'); //透传内容，点击消息后触发透传数据
        $template->set_title($title); //通知栏标题
        $template->set_text($content); //通知栏内容
        $template->set_logo($this->config['logo']);
        $template->set_isRing($this->config['is_ring']);
        $template->set_isVibrate($this->config['is_vibrate']);
        $template->set_isClearable($this->config['is_clearable']);
        return $template;
    }
}
