<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('action_report', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('report_id')
                ->constrained("reports")
                ->onUpdate('cascade');
            $table->foreignUuid('action_id')
                ->constrained("actions")
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_report');
    }
};
