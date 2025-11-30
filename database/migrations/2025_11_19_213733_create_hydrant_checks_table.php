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
        Schema::create('hydrant_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('hydrant_id')->constrained('hydrants')->restrictOnDelete();
            $table->foreignId('group_id')->constrained('groups')->restrictOnDelete();
            $table->date('date_check');
            $table->foreignId('zone_id')->constrained('zones')->restrictOnDelete();
            $table->foreignId('building_id')->constrained('buildings')->restrictOnDelete();
            $table->string('location');
            $table->foreignId('extinguisher_condition_id')->constrained('extinguisher_conditions')->restrictOnDelete();
            $table->foreignId('hydrant_door_id')->constrained('hydrant_doors')->restrictOnDelete();
            $table->foreignId('hydrant_coupling_id')->constrained('hydrant_couplings')->restrictOnDelete();
            $table->foreignId('hydrant_main_valve_id')->constrained('hydrant_main_valves')->restrictOnDelete();
            $table->foreignId('hydrant_hose_id')->constrained('hydrant_hoses')->restrictOnDelete();
            $table->foreignId('hydrant_nozzle_id')->constrained('hydrant_nozzles')->restrictOnDelete();
            $table->foreignId('hydrant_safety_marking_id')->constrained('hydrant_safety_markings')->restrictOnDelete();
            $table->foreignId('hydrant_guide_id')->constrained('hydrant_guides')->restrictOnDelete();
            $table->foreignId('hydrant_type_id')->constrained('hydrant_types')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['date_check', 'zone_id']);
            $table->index(['hydrant_id', 'date_check']);
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
        Schema::dropIfExists('hydrant_checks');
    }
};
