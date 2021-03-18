<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commerce_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->string('coupon', 50)->nullable();
            $table->float('discount_value')->nullable();
            $table->string('discount_type', 20)->nullable();
            $table->integer('limit')->nullable();
            $table->tinyInteger('active')->nullable();

            $table->timestamp('rule_period_from')->nullable();
            $table->timestamp('rule_period_to')->nullable();

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
        Schema::dropIfExists('commerce_coupons');
    }
}
