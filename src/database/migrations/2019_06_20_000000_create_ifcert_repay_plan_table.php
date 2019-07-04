<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateIfcertRepayPlanTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_repay_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('user_idcard_hash');
            $table->string('source_product_code');
            $table->integer('total_issue');
            $table->integer('issue');
            $table->string('replan_id');
            $table->float('cur_fund');
            $table->float('cur_interest');
            $table->float('cur_service_charge');
            $table->dateTime('repay_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ifcert_repay_plan');
    }
}