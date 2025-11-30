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
        Schema::create('apars', function (Blueprint $table) {
            $table->id();
            $table->string('number_apar')->unique();
            $table->string('location');
            $table->decimal('weight_of_extinguiser', 8, 2);
            $table->text('description')->nullable(); 
            $table->date('expired_date')->nullable(); 
            $table->string('qr_code')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('zone_id')->constrained('zones')->restrictOnDelete();
            $table->foreignId('building_id')->constrained('buildings')->restrictOnDelete();
            $table->foreignId('floor_id')->constrained('floors')->restrictOnDelete(); 
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete();
            $table->foreignId('apar_type_id')->constrained('apar_types')->restrictOnDelete();
            $table->foreignId('extinguisher_condition_id')->constrained('extinguisher_conditions')->restrictOnDelete();
            $table->timestamps();

            $table->index(['zone_id','building_id', 'floor_id']); 
            $table->index(['extinguisher_condition_id', 'is_active']); 
            $table->index(['brand_id', 'apar_type_id']); 
            $table->index('expired_date');
            
            $table->index('number_apar'); 
            $table->index('location'); 
            $table->index('qr_code'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apars');
    }
};
