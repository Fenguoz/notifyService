<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class NotifyTemplateConfigTableSeeder extends Seeder
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
            ['id' => 1, 'notify_code' => 'Sms', 'template_id' => 1, 'action_id' => 5, 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 2, 'notify_code' => 'Email', 'template_id' => 2, 'action_id' => 5, 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 3, 'notify_code' => 'GeTui', 'template_id' => 3, 'action_id' => 5, 'created_at' => $datetime, 'updated_at' => $datetime],
            ['id' => 4, 'notify_code' => 'Wechat', 'template_id' => 4, 'action_id' => 5, 'created_at' => $datetime, 'updated_at' => $datetime],
        ];
    
        foreach ($items as $item) {
            \App\Model\NotifyTemplateConfig::create($item);
        }
    }
}
