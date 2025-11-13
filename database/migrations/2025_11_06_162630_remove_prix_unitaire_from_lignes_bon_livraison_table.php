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
        Schema::table('lignes_bon_livraison', function (Blueprint $table) {
            $table->dropColumn('prix_unitaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lignes_bon_livraison', function (Blueprint $table) {
            $table->decimal('prix_unitaire', 15, 2)->default(0)->after('quantite');
        });
    }
};
