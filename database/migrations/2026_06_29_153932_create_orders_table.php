<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone')->nullable();
            
            // 📏 LES MENSURATIONS BRUTES (en cm)
            $table->integer('shoulder_measurement'); // Épaules
            $table->integer('chest_measurement');    // Poitrine
            $table->integer('waist_measurement');    // Taille
            $table->integer('hip_measurement');      // Hanches
            
            // ✂️ CRITÈRES MORPHOLOGIQUES SUPPLÉMENTAIRES
            $table->string('buste_length')->default('normal'); // court, normal, long
            $table->string('posture_type')->default('standard'); // cambrée, normale, voûtée
            $table->integer('arm_length')->nullable();
            $table->integer('total_length')->nullable(); // Longueur vêtement (robe, pantalon...)

            // 🧠 LE MOTEUR MORPHOCORE (Résultats dynamiques)
            $table->string('dominant_morphology')->nullable(); // ex: 'A' (le pourcentage le plus élevé)
            $table->json('morphology_percentages')->nullable(); // Stockera : {"A": 86, "V": 66, "X": 18}
            
            // 📁 SUIVI DE LA COMMANDE
            $table->string('status')->default('En attente');
            $table->unsignedBigInteger('user_id')->nullable(); // Couturier assigné
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
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
        Schema::dropIfExists('orders');
    }
};