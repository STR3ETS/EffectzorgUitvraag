<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colleagues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->string('name', 255)->nullable();
            $table->string('function', 255)->nullable();
            $table->text('wishes')->nullable();
            $table->text('pain_points')->nullable();
            $table->text('desired_workflow')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colleagues');
    }
};
