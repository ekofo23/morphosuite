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
    Schema::create('mannequins', function (Blueprint $table) {
        $table->id();
        // Lien avec la commande ou le client (selon ta structure actuelle, ici lié à l'order)
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        
        // Nom du profil pour s'y retrouver dans l'historique (ex: "Mannequin - Marie")
        $table->string('profile_name');
        
        // Sauvegarde figée des mesures au moment de la création
        $table->integer('shoulder_measurement');
        $table->integer('chest_measurement');
        $table->integer('waist_measurement');
        $table->integer('hip_measurement');
        $table->string('dominant_morphology');
        
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
        Schema::dropIfExists('mannequins');
    }
};
