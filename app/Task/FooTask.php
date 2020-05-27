<?php
namespace App\Task;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

/**
 * @Crontab(name="Foo", rule="* * * * * *", callback="execute", memo="这是一个示例的定时任务")
 */
class FooTask
{

    /**
     * @Inject()
     * @var \Hyperf\Contract\StdoutLoggerInterface
     */
    private $logger;

    protected $appKey = '2SC82DGlQOA06qs0ODP7E6';
    protected $appId = 'Dhaj8Y9SEH88h7uivFbpP';
    protected $appSecret = 'SxefwZSXCX8AXr9GciZCD9';
    protected $masterSecret = 'glEN5rR5nf72ehnhV7ocQ1';


    public function execute()
    {

        $this->Push('c28c1a9a771bb323f6d30426122e6ab0', 'test', 'test-content');

        // $this->logger->info(date('Y-m-d H:i:s', time()));
        // $conf = DB::table('MessageConfig')->first();
        // if(empty($conf)){
        //     echo '通知消息配置错误';
        //     exit;
        // }
        // // define('APPKEY', $conf->AppKey);
        // // define('APPID', $conf->AppId);
        // // define('MASTERSECRET', $conf->MasterSecret);
        
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

    private function Push($cid, $title, $content){
        $igt = new \IGeTui('',$this->appKey,$this->masterSecret);

        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->IGtNotyPopLoadTemplate($title, $content);

        //定义"SingleMessage"
        $message = new \IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //接收方
        $target = new \IGtTarget();
        $target->set_appId($this->appId);
        $target->set_clientId($cid);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);
        }catch(\RequestException $e){
            $requstId = $e->getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target,$requstId);
        }
    }

    private function IGtNotyPopLoadTemplate($title, $content){
        $template =  new \IGtNotificationTemplate();
        $template->set_appId($this->appId);//应用appid
        $template->set_appkey($this->appKey);//应用appkey
        $template->set_transmissionType(1);//透传消息类型，Android平台控制点击消息后是否启动应用
        $template->set_transmissionContent('test');//透传内容，点击消息后触发透传数据
        $template->set_title($title);//通知栏标题
        $template->set_text($content);//通知栏内容
    //    $template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo，不设置使用默认程序图标
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        return $template;
    }
}
