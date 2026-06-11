<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->string('system_name', 255);
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->string('action', 100)->nullable();
            $table->string('test_account', 500)->nullable();
            $table->string('demo_url', 500)->nullable();
            $table->string('access_contact', 255)->nullable();
            $table->string('modules', 500)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_accesses');
    }
};
