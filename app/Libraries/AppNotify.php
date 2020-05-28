<?php

namespace App\Libraries;

use App\Model\Setting;
use Hyperf\Di\Annotation\Inject;

class AppNotify
{
    /**
     * @Inject
     * @var Setting
     */
    protected $setting;

    protected $appId = ''; //应用appid
    protected $appKey = ''; //应用appkey
    protected $appSecret = '';
    protected $masterSecret = '';
    protected $isOffline = true; //是否离线
    protected $logo = ''; //通知栏logo，不设置使用默认程序图标
    protected $isRing = true; //是否响铃
    protected $isVibrate = true; //是否震动
    protected $isClearable = true; //通知栏是否可清除
    protected $offlineExpireTime = 43200000; //离线时间(s)

    public function __construct()
    {
        $setting = $this->setting->getListByModule('app');
        $this->appId = $setting['app_id'];
        $this->appKey = $setting['app_key'];
        $this->appSecret = $setting['app_secret'];
        $this->masterSecret = $setting['app_master_secret'];
        $this->logo = $setting['app_logo'];
        $this->isOffline = $this->filter($setting['app_is_offline']);
        $this->isRing = $this->filter($setting['app_is_ring']);
        $this->isVibrate = $this->filter($setting['app_is_vibrate']);
        $this->isClearable = $this->filter($setting['app_is_clearable']);
        $this->offlineExpireTime = $setting['app_offline_expire_time'];
    }

    public function filter($value)
    {
        switch ($value) {
            case 'false':
                $real_value = false;
                break;
            case 'true':
                $real_value = true;
                break;
            default:
                $real_value = trim($value);
                break;
        }
        return $real_value;
    }

    public function push($cid, $title, $content)
    {
        $igt = new \IGeTui('', $this->appKey, $this->masterSecret);

        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->IGtNotyPopLoadTemplate($title, $content);

        //定义"SingleMessage"
        $message = new \IGtSingleMessage();

        $message->set_isOffline($this->isOffline);
        $message->set_offlineExpireTime($this->offlineExpireTime);
        $message->set_data($template); //设置推送消息类型
        //接收方
        $target = new \IGtTarget();
        $target->set_appId($this->appId);
        $target->set_clientId($cid);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);
        } catch (\RequestException $e) {
            $requstId = $e->getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
        }
    }

    private function IGtNotyPopLoadTemplate($title, $content)
    {
        $template =  new \IGtNotificationTemplate();
        $template->set_appId($this->appId);
        $template->set_appkey($this->appKey);
        $template->set_transmissionType(1); //透传消息类型，Android平台控制点击消息后是否启动应用
        $template->set_transmissionContent('test'); //透传内容，点击消息后触发透传数据
        $template->set_title($title); //通知栏标题
        $template->set_text($content); //通知栏内容
        $template->set_logo($this->logo);
        $template->set_isRing($this->isRing);
        $template->set_isVibrate($this->isVibrate);
        $template->set_isClearable($this->isClearable);
        return $template;
    }
}
