<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('threat_reports', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('report_number')->unique();
            $table->string('title');
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('kingdom_id')->nullable()->constrained('kingdoms')->nullOnDelete();
            $table->string('type')->index();
            $table->string('level')->index();
            $table->unsignedTinyInteger('level_severity')->default(0); // derived from level by the model, kept as a real column so it stays cursor-paginate/orderBy compatible
            $table->string('status')->index();
            $table->unsignedInteger('sightings')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('threat_reports');
    }
};