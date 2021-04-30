<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('postal_area')->nullable();
            $table->string('project_mang_name')->nullable();
            $table->string('project_mang_mobile')->nullable();
            $table->string('project_mang_email')->nullable();
            $table->string('onsite_name')->nullable();
            $table->string('onsite_mobile')->nullable();
            $table->string('onsite_email')->nullable();
            $table->string('project_type')->nullable();
            $table->string('project_status')->nullable();
            $table->string('property_area')->nullable();
            $table->string('no_of_floors')->nullable();
            $table->integer('building_year')->nullable();
            $table->string('last_refurbished')->nullable();
            $table->string('env_report')->nullable();
            $table->string('fdv_document')->nullable();
            $table->string('ambition')->nullable();
            $table->date('project_start_date')->nullable();
            $table->date('project_catalog_date')->nullable();
            $table->date('project_avail_date')->nullable();
            $table->date('project_avail_end_date')->nullable();
            $table->text('note')->nullable();
            $table->string('billing_project_company')->nullable();
            $table->string('billing_orgno')->nullable();
            $table->string('billing_project_number')->nullable();
            $table->string('billing_customer_ref')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_postal_area')->nullable();
            $table->string('credit_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
