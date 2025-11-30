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
        Schema::create('apar_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('apar_id')->constrained('apars')->restrictOnDelete();
            $table->foreignId('group_id')->constrained('groups')->restrictOnDelete();
            $table->date('date_check');
            $table->foreignId('zone_id')->constrained('zones')->restrictOnDelete();
            $table->foreignId('building_id')->constrained('buildings')->restrictOnDelete();
            $table->string('location');
            $table->foreignId('extinguisher_condition_id')->constrained('extinguisher_conditions')->restrictOnDelete();
            $table->foreignId('apar_pressure_id')->constrained('apar_pressures')->restrictOnDelete();
            $table->foreignId('apar_cylinder_id')->constrained('apar_cylinders')->restrictOnDelete();
            $table->foreignId('apar_pin_seal_id')->constrained('apar_pin_seals')->restrictOnDelete();
            $table->foreignId('apar_hose_id')->constrained('apar_hoses')->restrictOnDelete();
            $table->foreignId('apar_handle_id')->constrained('apar_handles')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            // ADD INDEXES untuk performance
            $table->index(['date_check', 'zone_id']);
            $table->index(['apar_id', 'date_check']);
            $table->index(['user_id', 'date_check']);
            $table->index(['group_id', 'date_check']);
            $table->index(['building_id', 'zone_id']);
            $table->index(['extinguisher_condition_id', 'date_check']);
            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apar_checks');
    }
};
