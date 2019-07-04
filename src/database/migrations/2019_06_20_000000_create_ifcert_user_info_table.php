<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertUserInfoTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_user_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->unsignedTinyInteger('user_type');
            $table->unsignedTinyInteger('user_attr');
            $table->dateTime('user_create_time');
            $table->string('username');
            $table->tinyInteger('countries');
            $table->unsignedTinyInteger('card_type');
            $table->string('user_idcard');
            $table->string('user_idcard_hash');
            $table->string('user_phone');
            $table->string('user_phone_hash');
            $table->string('user_uuid');
            $table->string('user_lawperson')->nullable();
            $table->integer('user_fund')->nullable();
            $table->string('user_province')->nullable();
            $table->string('user_address')->nullable();
            $table->date('register_date')->nullable();
            $table->string('user_sex')->nullable();
            $table->text('user_bank_account');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_user_info');
    }
}