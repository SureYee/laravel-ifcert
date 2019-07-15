<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_request_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('batch_num')->comment('批次号')->unique();
            $table->dateTime('send_time')->comment('请求时间');
            $table->integer('inf_type')->comment('接口类型');
            $table->tinyInteger('data_type')->comment('请求类型');
            $table->text('url')->comment('请求接口地址');
            $table->integer('count')->comment('请求数据条数');
            $table->boolean('has_reported')->default(0)->comment('是否已经上报');
            $table->boolean('has_checked')->default(0)->comment('是否已经check');
            $table->string('checked_message')->nullable()->comment('check结果');
            $table->string('error_message')->nullable()->comment('上报失败错误信息');
            $table->string('error_code')->nullable()->comment('上报失败错误码');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_request_logs');
    }
}