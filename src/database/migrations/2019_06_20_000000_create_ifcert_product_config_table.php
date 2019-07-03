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
            $table->string('');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_request_logs');
    }
}