<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateActionTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('action', function (Blueprint $table) {
            $table->index(['action', 'module'], 'module_action');
            $table->bigIncrements('id')->comment('主键ID');
            $table->string('name', 255)->nullable(false)->comment('文章标题');
            $table->unsignedInteger('parent_id')->default(0)->comment('父ID');
            $table->string('module')->nullable(false)->comment('模块');
            $table->string('action')->default('')->comment('行为标识');
            $table->string('routing_key')->nullable(false)->comment('消息路由key');
            $table->timestamps();
        });
        Db::statement("ALTER TABLE `action` comment'动作行为'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action');
    }
}
