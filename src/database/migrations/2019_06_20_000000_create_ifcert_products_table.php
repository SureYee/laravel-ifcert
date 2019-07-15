<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertProductsTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('source_financing_code')->comment('产品信息编号');
            $table->dateTime('financing_start_time')->comment('发布时间');
            $table->string('product_name')->comment('产品名称');
            $table->float('rate', 7, 6)->comment('预期年化利率(参考回报率)');
            $table->float('min_rate', 7, 6)->nullable()->comment('最小预期年化利率');
            $table->float('max_rate', 7, 6)->nullable()->comment('最大预期年化利率');
            $table->unsignedInteger('term')->comment('产品期限 (服务期限)(天)');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ifcert_products');
    }
}