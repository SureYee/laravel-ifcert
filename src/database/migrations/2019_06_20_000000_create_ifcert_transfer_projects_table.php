<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertTransferProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_transfer_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('transfer_id');
            $table->unsignedTinyInteger('from_type');
            $table->string('fin_claim_id');
            $table->string('user_idcard_hash');
            $table->string('source_product_code');
            $table->float('transfer_amount');
            $table->float('transfer_interest_rate', 7, 6);
            $table->float('float_money');
            $table->date('transfer_date');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_transfer_projects');
    }
}