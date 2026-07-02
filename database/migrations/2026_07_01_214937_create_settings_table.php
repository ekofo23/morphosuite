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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // Ex: 'theme_sidebar_color'
        $table->text('value')->nullable(); // Stocke la valeur actuelle
        $table->string('type')->default('text'); // text, color, file, select (pour le rendu du formulaire)
        $table->string('group')->default('general'); // design, auth, general (pour organiser les onglets)
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
        Schema::dropIfExists('settings');
    }
};
