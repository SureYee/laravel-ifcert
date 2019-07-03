<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertCreditorTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_creditor', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('fin_claim_id')->comment('初始债权编号');
            $table->string('source_product_code')->comment('散标信息编号');
            $table->string('user_id_hash')->comment('出借用户证件号hash值');
            $table->float('inv_amount')->comment('出借金额(元)');
            $table->float('inv_rate', 7, 6)->comment('出借预期年化利率');
            $table->dateTime('inv_time')->comment('出借计息时间');
            $table->float('red_package')->comment('出借红包(满减)(元)');
            $table->date('lock_time')->comment('出借成功后，不允许债权转让的时间期限。格式 yyyy-MM-dd');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_request_logs');
    }
}