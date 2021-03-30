<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class NotifyTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = date('Y-m-d H:i:s');
        $items = [
            ['id' => 1, 'name' => '短信验证码', 'code' => '5a9599****46b953', 'content' => '您的验证码是#code#。如非本人操作，请忽略本短信', 'param' =>'{"#code#":"code"}', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 2, 'name' => '邮箱验证码', 'code' => 'xxx', 'content' => '尊敬的xx用户：

            您好！
            
            您的验证码为%code%，有效期10分钟，如非本人操作，请忽略！
            
            如果您有任何疑问或建议，可以通过邮箱 xxx@sina.com 联系我们
            本邮件由xxx平台系统自动发出，请勿直接回复
            
            xxx平台客户服务部', 'param' =>'{"%code%":"code"}', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 3, 'name' => 'App注册成功通知', 'code' => 'getui', 'content' => '恭喜成功注册成为xxx平台会员！', 'param' =>'', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 4, 'name' => '微信注册成功通知', 'code' => '19cQrKVR3Cgiw****9xkQDhtsl4bBhCk', 'content' => '{{first.DATA}}
            账号：{{keyword1.DATA}}
            时间：{{keyword2.DATA}}
            {{remark.DATA}}', 'param' =>'', 'created_at' => $datetime, 'updated_at' => $datetime],
        ];
    
        foreach ($items as $item) {
            \App\Model\NotifyTemplate::create($item);
        }
    }
}
