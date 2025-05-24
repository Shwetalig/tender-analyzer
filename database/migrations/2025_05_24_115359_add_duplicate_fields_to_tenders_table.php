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
        $table->boolean('is_duplicate')->default(false);
        $table->foreignId('duplicate_of_id')->nullable()->constrained('tenders')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('tenders', function (Blueprint $table) {
        $table->dropColumn('is_duplicate');
        $table->dropForeign(['duplicate_of_id']);
        $table->dropColumn('duplicate_of_id');
    });
}

};
