<?php

use App\Models\Author;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('category')->index();
            $table->string('era')->index();
            $table->string('date')->nullable(); // free-form: "Year 411", "Year 512–529", "Unknown"
            $table->foreignIdFor(Author::class)->nullable()->constrained()->nullOnDelete();
            $table->string('importance')->index();
            $table->unsignedTinyInteger('importance_level')->default(0); // derived from importance by the model, kept as a real column so it stays cursor-paginate/orderBy compatible
            $table->boolean('confidential')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};