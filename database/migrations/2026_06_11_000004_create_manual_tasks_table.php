<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colleague_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->text('description')->nullable();
            $table->string('frequency', 100)->nullable();
            $table->string('time_per_task', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_tasks');
    }
};
