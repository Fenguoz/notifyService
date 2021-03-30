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
            $table->unique('code', 'code');
            $table->bigIncrements('id');
            $table->string('name', 255)->nullable(false)->comment('名称');
            $table->string('code', 50)->nullable(false)->comment('第三方标识码 如模板ID');
            $table->text('content')->comment('内容');
            $table->text('param')->nullable()->comment('模板变量');
            $table->timestamps();
        });
        Db::statement("ALTER TABLE `notify_template` comment'模板'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notify_template');
    }
}
