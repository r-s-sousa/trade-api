<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_team_one')->constrained('teams');
            $table->foreignId('id_team_two')->constrained('teams');
            $table->foreignId('id_championship')->constrained('championships');
            $table->string('stage', 50);
            $table->integer('team_one_goals');
            $table->integer('team_two_goals');
            $table->timestamps();
            $table->unique(['stage', 'id_team_one', 'id_team_two', 'id_championship'], 'game_stage_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
