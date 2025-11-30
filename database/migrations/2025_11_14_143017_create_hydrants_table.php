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
        Schema::create('hydrants', function (Blueprint $table) {
            $table->id();
            $table->string('number_hydrant')->unique();
            $table->string('location');
            $table->string('description')->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('zone_id')->constrained('zones')->restrictOnDelete();
            $table->foreignId('building_id')->constrained('buildings')->restrictOnDelete();
            $table->foreignId('floor_id')->constrained('floors')->restrictOnDelete();
            $table->foreignId('brand_id')->constrained()->restrictOnDelete();
            $table->foreignId('hydrant_type_id')->constrained('hydrant_types')->restrictOnDelete();
            $table->foreignId('extinguisher_condition_id')->constrained('extinguisher_conditions')->restrictOnDelete();
            $table->timestamps();

            $table->index(['zone_id', 'building_id', 'floor_id']);
            $table->index(['extinguisher_condition_id', 'is_active']);
            $table->index(['brand_id', 'hydrant_type_id']);
            
            $table->index('number_hydrant');
            $table->index('location');
            $table->index('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydrants');
    }
};
