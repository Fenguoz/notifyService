<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateNotifyTemplateTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notify_template', function (Blueprint $table) {
            $table->index(['notify_code', 'template_id', 'action_id'], 'notify_action_template');
            $table->bigIncrements('id');
            $table->string('notify_code', 50)->nullable(false)->comment('标识码');
            $table->unsignedInteger('template_id')->nullable(false)->comment('模版ID');
            $table->unsignedInteger('action_id')->nullable(false)->comment('动作行为ID');
            $table->timestamps();
        });
        Db::statement("ALTER TABLE `notify_template` comment'消息模板配置'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notify_template');
    }
}
