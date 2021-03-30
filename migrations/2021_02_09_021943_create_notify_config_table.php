<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateNotifyConfigTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notify_config', function (Blueprint $table) {
            $table->primary('code');
            $table->string('code', 50)->nullable(false)->comment('标识码');
            $table->string('name', 50)->nullable(false)->comment('名称');
            $table->string('desc', 255)->default('')->comment('描述');
            $table->text('config')->comment('配置');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态 1开启 0关闭');
            $table->unsignedTinyInteger('sort')->default(100)->comment('排序');
            $table->timestamps();
        });
        Db::statement("ALTER TABLE `notify_config` comment'消息配置'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notify_config');
    }
}
