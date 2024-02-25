<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->ulid("serial_number")->unique();
            $table->text('description')->nullable();
            $table->string('shift')->nullable();
            $table->enum('status', StatusEnum::getValues())->default(StatusEnum::NORMAL);
            $table->string('venue')->nullable();
            $table->string('reporter')->nullable();
            $table->boolean('approved')->default(false);
            $table->foreignId('user_id')
                ->constrained("users")
                ->onUpdate('cascade');
            $table->foreignId('category_id')->nullable()
                ->constrained("categories")
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
