<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excel_files', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('extention');
            $table->string('path')->nullable();
            $table->binary('file')->nullable();
            $table->text('errors')->nullable();
            $table->integer('rows')->default(0);
            $table->integer('columns')->default(0);
            $table->string('status');
            $table->boolean('processed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_files');
    }
};
