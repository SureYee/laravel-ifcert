<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIfcertScatterInvestTable extends Migration
{
    public function up()
    {
        Schema::create('ifcert_scatter_invest', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->nullable()->comment('请求id');
            $table->dateTime('product_start_time');
            $table->string('product_name');
            $table->string('source_product_code');
            $table->string('user_idcard_hash');
            $table->unsignedInteger('loan_use');
            $table->text('loan_describe');
            $table->float('loan_rate', 7, 6);
            $table->float('amount');
            $table->float('surplus_amount');
            $table->unsignedInteger('term');
            $table->unsignedTinyInteger('pay_type');
            $table->float('service_cost');
            $table->unsignedTinyInteger('loan_type');
            $table->tinyInteger('security_type')->default(-1);
            $table->unsignedInteger('security_company_amount')->nullable();
            $table->text('security_company_name')->nullable();
            $table->text('security_company_idcard')->nullable();
            $table->boolean('is_financing_assure')->nullable();
            $table->float('security_amount')->nullable();
            $table->string('project_source');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ifcert_scatter_invest');
    }
}