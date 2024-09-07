<?php

use App\Models\Destination;
use App\Models\Tour;
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
        Schema::create('destination_tour', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tour::class);
            $table->foreignIdFor(Destination::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_tour');
    }
};
