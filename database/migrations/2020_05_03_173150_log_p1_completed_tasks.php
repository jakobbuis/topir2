<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('counts', function (Blueprint $table) {
            $table->integer('completed_p1')->after('completed')->default(0);
        });
    }

    public function down()
    {
        Schema::table('counts', function (Blueprint $table) {
            $table->dropColumn('completed_p1');
        });
    }
};
