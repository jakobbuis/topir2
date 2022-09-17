<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('counts', function (Blueprint $table) {
            $table->dropColumn('completed');
            $table->integer('completed_p2')->after('completed_p1')->default(0);
            $table->integer('completed_p3')->after('completed_p2')->default(0);
            $table->integer('completed_p4')->after('completed_p3')->default(0);
        });
    }

    public function down()
    {
        Schema::table('counts', function (Blueprint $table) {
            $table->integer('completed')->default(0);
            $table->dropColumn('completed_p2');
            $table->dropColumn('completed_p3');
            $table->dropColumn('completed_p4');
        });
    }
};
