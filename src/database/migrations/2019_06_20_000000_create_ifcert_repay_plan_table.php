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
            $table->unsignedInteger('request_id');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_request_logs');
    }
}