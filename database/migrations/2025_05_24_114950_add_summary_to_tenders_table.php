<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->text('summary')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn('summary');
        });
    }


    /**
     * Reverse the migrations.
     */
};
