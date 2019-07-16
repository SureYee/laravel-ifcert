<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertParticularsTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_particulars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->string('trans_id');
            $table->string('source_financing_code');
            $table->integer('trans_type');
            $table->float('trans_money', 9, 2);
            $table->string('user_idcard_hash');
            $table->dateTime('trans_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ifcert_particulars');
    }
}