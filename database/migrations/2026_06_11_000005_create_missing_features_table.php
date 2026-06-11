<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missing_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colleague_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->string('system_name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('workaround', 500)->nullable();
            $table->string('extra_time', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missing_features');
    }
};
