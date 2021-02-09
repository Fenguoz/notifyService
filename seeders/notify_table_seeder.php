<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class NotifyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['code' => 'GeTui', 'name' => '个推', 'desc' => '消息推送、推送技术、推送SDK、推送服务、安卓推送、app推广、一键认证、应用统计、用户画像、个推大数据、个推开发者服务', 'config' => '{"app_id":"xxx","app_key":"xxx","app_secret":"xxx","master_secret":"xxx"}', 'status' => 1, 'sort' => 100],
            ['code' => 'Mail', 'name' => '邮件', 'desc' => 'PHPMailer – 用于PHP的全功能电子邮件创建和传输类', 'config' => '{"host":"smtp.live.com","port":587,"smtp_secure":"ssl","username":"xxx@outlook.com","password":"xxx","send_mail":"xxxn@outlook.com","send_nickname":"xxx","attachment":""}', 'status' => 1, 'sort' => 100],
            ['code' => 'Sms', 'name' => '短信', 'desc' => '一款满足你的多种发送需求的短信发送组件', 'config' => '{"timeout":5,"default":{"gateways":["moduyun"]},"gateways":{"moduyun":{"signId":"xxx","accesskey":"xxx","secretkey":"xxx","type":0}}}', 'status' => 1, 'sort' => 100],
            ['code' => 'Wechat', 'name' => '微信', 'desc' => '也许是世界上最好用的微信开发 SDK', 'config' => '{"officialAccount":{"app_id":"xxx","secret":"xxx","response_type":"array"},"miniProgram":{"app_id":"xxx","secret":"xxx","response_type":"array"}}', 'status' => 1, 'sort' => 100],
        ];

        foreach ($items as $item) {
            \App\Model\Notify::create($item);
        }
    }
}
