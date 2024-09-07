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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('images');
            $table->string('duration');
            $table->decimal('price', 10, 2);
            $table->integer('group_size');
            $table->string('language');
            $table->text('overview');
            $table->text('duration_details');
            $table->unsignedBigInteger('category_tour_id');
            $table->foreign('category_tour_id')->references('id')->on('category_tours');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
