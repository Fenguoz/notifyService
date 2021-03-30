<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class NotifyActionTableSeeder extends Seeder
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
            ['id' => 1, 'name' => '公共', 'parent_id' => 0, 'module' => 'public', 'action' => '', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 2, 'name' => '会员', 'parent_id' => 0, 'module' => 'user', 'action' => '', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 3, 'name' => '商品', 'parent_id' => 0, 'module' => 'goods', 'action' => '', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 4, 'name' => '订单', 'parent_id' => 0, 'module' => 'order', 'action' => '', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 5, 'name' => '注册', 'parent_id' => 2, 'module' => 'user', 'action' => 'register', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 6, 'name' => '登陆', 'parent_id' => 2, 'module' => 'user', 'action' => 'login', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 7, 'name' => '忘记密码', 'parent_id' => 2, 'module' => 'user', 'action' => 'forget_password', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 8, 'name' => '设置交易密码', 'parent_id' => 2, 'module' => 'user', 'action' => 'set_pay_password', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 9, 'name' => '忘记交易密码', 'parent_id' => 2, 'module' => 'user', 'action' => 'forget_pay_password', 'created_at' => $datetime, 'updated_at' => $datetime],
        ];
    
        foreach ($items as $item) {
            \App\Model\NotifyAction::create($item);
        }
    }
}
