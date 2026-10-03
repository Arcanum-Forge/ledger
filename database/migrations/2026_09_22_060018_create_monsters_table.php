<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('classification')->index();
            $table->string('habitat')->index();
            $table->foreignId('kingdom_id')->nullable()->constrained('kingdoms')->nullOnDelete();
            $table->string('threat')->index();
            $table->unsignedTinyInteger('threat_level')->default(0); // derived from threat by the model, kept as a real column so it stays cursor-paginate/orderBy compatible
            $table->unsignedInteger('sightings')->default(0)->index();
            $table->string('status')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monsters');
    }
};