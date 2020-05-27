<?php

namespace App\Task;

use App\Model\Setting;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

/**
 * @Crontab(name="AppNotify", rule="* * * * *", callback="execute", memo="App消息推送")
 */
class AppNotify
{

    /**
     * @Inject()
     * @var \Hyperf\Contract\StdoutLoggerInterface
     */
    private $logger;

    /**
     * @Inject
     * @var Setting
     */
    protected $setting;

    protected $appKey = ''; //应用appkey
    protected $appId = ''; //应用appid
    protected $appSecret = '';
    protected $masterSecret = '';
    protected $isOffline = true; //是否离线
    protected $logo = ''; //通知栏logo，不设置使用默认程序图标
    protected $isRing = true; //是否响铃
    protected $isVibrate = true; //是否震动
    protected $isClearable = true; //通知栏是否可清除
    protected $offlineExpireTime = 43200000; //离线时间(s)

    public function execute()
    {
        $setting = $this->setting->getListByModule('app');
        $this->appKey = $setting['app_key'];
        $this->appId = $setting['app_id'];
        $this->appSecret = $setting['app_secret'];
        $this->masterSecret = $setting['app_master_secret'];
        $this->logo = $setting['app_logo'];

        // $this->Push('c28c1a9a771bb323f6d30426122e6ab0', 'test', 'test-content');

        $this->logger->info(date('Y-m-d H:i:s', time()));

        // $redis = $this->container->get(RedisFactory::class)->get('default');
        // $server = $this->container->get(ServerFactory::class)->getServer()->getServer();

        // $param = json_decode($data, true);
        // $notify = $this->notifyService->getList([
        //     'receiver_id' => $param['receiver_id'],
        //     'receiver_type' => $param['receiver_type'],
        //     'is_read' => 0,
        // ]);

        // foreach($notify as $v){
        //     $user_key = 'ws_' . $param['receiver_type'] . '_' . $param['receiver_id'];
        //     $fd = $redis->get($user_key);
        //     if($fd){
        //         $result = $server->push((int) $fd, json_encode([
        //             'code' => 200,
        //             'message' => 'success',
        //             'data' => $v
        //         ]));
        //         if ($result == 1) { //推送成功
        //             $this->notifyService->read($v->id);
        //         } else { //推送失败
        //             // to do ...延迟推送机制
        //         }
        //     }
        // }

        // $members = DB::table('Members')
        //     ->where('MessageSendDate', '<>', intval(date('Ymd')))
        //     ->where(DB::raw('SumReward/SumPay'), '>=', $conf->Ratio)
        //     ->select('Id','ClientId')
        //     ->paginate(1000);
        // foreach($members as $item){
        //     if(empty($item->ClientId)) continue;
        //     try{
        //         $this->Push($item->ClientId, $conf->Title, $conf->Content);
        //         DB::table('Members')->where('Id', $item->Id)->update(['MessageSendDate' => intval(date('Ymd'))]);
        //     } catch(\Exception $e){
        //         continue;
        //     }
        // }
    }

    private function Push($cid, $title, $content)
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
