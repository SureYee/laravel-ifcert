<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertProductConfigTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_product_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('config_id');
            $table->string('fin_claim_id');
            $table->string('source_financing_code');
            $table->string('user_idcard_hash');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_product_config');
    }
}