<?php

use App\Models\Region;
use App\Models\Ruler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kingdoms', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Region::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Ruler::class)->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title');

            $table->text('description');

            $table->string('population');

            $table->string('alignment')->index();
            $table->unsignedTinyInteger('threat')->index();

            $table->string('founded');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kingdoms');
    }
};