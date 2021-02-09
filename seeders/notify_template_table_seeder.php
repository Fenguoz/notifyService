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
        $items = [
            ['id' => 1, 'notify_code' => 'Sms', 'template_id' => 1, 'action_id' => 5],
            ['id' => 2, 'notify_code' => 'Email', 'template_id' => 2, 'action_id' => 5],
            ['id' => 3, 'notify_code' => 'GeTui', 'template_id' => 3, 'action_id' => 5],
            ['id' => 4, 'notify_code' => 'Wechat', 'template_id' => 4, 'action_id' => 5],
        ];
    
        foreach ($items as $item) {
            \App\Model\NotifyTemplate::create($item);
        }
    }
}
