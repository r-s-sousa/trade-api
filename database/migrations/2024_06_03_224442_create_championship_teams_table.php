<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('championship_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_championship')->constrained('championships');
            $table->foreignId('id_team')->constrained('teams');
            $table->integer('team_points')->default(0);
            $table->integer('team_ranking')->default(0);
            $table->timestamps();
            $table->unique(['id_championship', 'id_team']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('championship_teams');
    }
};
