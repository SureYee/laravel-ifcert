<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertStatusTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('source_product_code');
            $table->unsignedTinyInteger('product_status');
            $table->dateTime('product_time');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_status');
    }
}