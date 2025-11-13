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
        Schema::table('bons_commande', function (Blueprint $table) {
            $table->string('reference_marche', 100)->nullable()->after('reference');
        });

        Schema::table('bons_livraison', function (Blueprint $table) {
            $table->string('reference_marche', 100)->nullable()->after('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bons_commande', function (Blueprint $table) {
            $table->dropColumn('reference_marche');
        });

        Schema::table('bons_livraison', function (Blueprint $table) {
            $table->dropColumn('reference_marche');
        });
    }
};
