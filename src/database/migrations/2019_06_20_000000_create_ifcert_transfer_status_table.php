<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertTransferStatusTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_transfer_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('transfer_id');
            $table->unsignedTinyInteger('transfer_status');
            $table->float('amount');
            $table->float('float_money');
            $table->dateTime('product_date');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_transfer_status');
    }
}