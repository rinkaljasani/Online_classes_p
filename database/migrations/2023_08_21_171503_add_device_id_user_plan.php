<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceIdUserPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('user_device_id')->nullable();
            $table->foreign('user_device_id')->references('id')->on('user_devices')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('expiry_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropForeign('user_plans_user_device_id_foreign'); // $table->dropForeign('table_name_consultant_id_foreign');
            $table->dropColumn(['user_device_id','expiry_at']);
        });
    }
}
