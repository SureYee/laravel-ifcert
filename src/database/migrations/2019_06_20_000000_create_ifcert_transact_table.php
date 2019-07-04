<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertTransactTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_transact', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('trans_id');
            $table->string('source_product_code')->default(-1);
            $table->string('source_product_name')->default(-1);
            $table->string('fin_claim_id')->default(-1);
            $table->string('transfer_id')->default(-1);
            $table->string('replan_id')->default(-1);
            $table->string('trans_type')->default(-1);
            $table->float('trans_money');
            $table->string('user_idcard_hash')->default(-1);
            $table->dateTime('trans_time');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_transact');
    }
}