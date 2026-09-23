<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asunto', function (Blueprint $table) {
            $table->decimal('valor_usd', 10, 2)->nullable()->default(0)->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asunto', function (Blueprint $table) {
            $table->dropColumn('valor_usd');
        });
    }
};
