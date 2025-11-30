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
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom baru
            $table->date('date_birth')->nullable()->after('email');
            $table->foreignId('employe_type_id')->nullable()->constrained('employee_types')->onDelete('set null')->after('date_birth');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null')->after('employe_type_id');
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null')->after('group_id');
            $table->foreignId('competency_id')->nullable()->constrained('competencies')->onDelete('set null')->after('position_id');
            $table->string('nip', 20)->nullable()->unique()->after('competency_id');
            
            // Tambah index untuk performance
            $table->index('nip');
            $table->index('employe_type_id');
            $table->index('group_id');
            $table->index('position_id');
            $table->index('competency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign keys dulu
            $table->dropForeign(['employe_type_id']);
            $table->dropForeign(['group_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['competency_id']);
            
            // Hapus kolom
            $table->dropColumn([
                'date_birth',
                'employe_type_id', 
                'group_id',
                'position_id',
                'competency_id',
                'nip'
            ]);
        });
    }
};
