<?php

use App\Models\Kingdom;
use App\Models\Leader;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factions', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->string('title');
            $table->text('description');

            $table->string('type')->index();

            $table->foreignIdFor(Leader::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();


            $table->foreignIdFor(Kingdom::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('members');

            $table->string('alignment')->index();
            $table->unsignedTinyInteger('influence')->index();

            $table->string('status')->index();
            $table->text('status_description');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factions');
    }
};