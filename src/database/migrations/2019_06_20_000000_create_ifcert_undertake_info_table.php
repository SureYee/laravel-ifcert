<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertUndertakeInfoTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_undertake_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('un_fin_claim_id');
            $table->string('transfer_id');
            $table->string('fin_claim_id');
            $table->string('user_idcard_hash');
            $table->float('take_amount');
            $table->float('take_interest');
            $table->float('float_money');
            $table->float('take_rate', 7, 6);
            $table->dateTime('take_time');
            $table->integer('red_package')->default(0);
            $table->date('lock_time');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_undertake_info');
    }
}