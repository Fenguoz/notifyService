<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'name' => '公共', 'parent_id' => 0, 'module' => 'public', 'action' => '', 'routing_key' => 'public.*'],
            ['id' => 2, 'name' => '会员', 'parent_id' => 0, 'module' => 'user', 'action' => '', 'routing_key' => 'user.*'],
            ['id' => 3, 'name' => '商品', 'parent_id' => 0, 'module' => 'goods', 'action' => '', 'routing_key' => 'goods.*'],
            ['id' => 4, 'name' => '订单', 'parent_id' => 0, 'module' => 'order', 'action' => '', 'routing_key' => 'order.*'],
            ['id' => 5, 'name' => '注册', 'parent_id' => 2, 'module' => 'user', 'action' => 'register', 'routing_key' => 'user.register'],
            ['id' => 6, 'name' => '登陆', 'parent_id' => 2, 'module' => 'user', 'action' => 'login', 'routing_key' => 'user.login'],
            ['id' => 7, 'name' => '忘记密码', 'parent_id' => 2, 'module' => 'user', 'action' => 'forget_password', 'routing_key' => 'user.forget_password'],
            ['id' => 8, 'name' => '设置交易密码', 'parent_id' => 2, 'module' => 'user', 'action' => 'set_pay_password', 'routing_key' => 'user.set_pay_password'],
            ['id' => 9, 'name' => '忘记交易密码', 'parent_id' => 2, 'module' => 'user', 'action' => 'forget_pay_password', 'routing_key' => 'user.forget_pay_password'],
        ];
    
        foreach ($items as $item) {
            \App\Model\Action::create($item);
        }
    }
}
