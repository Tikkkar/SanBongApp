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
        Schema::table('tournaments', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('description');
            $table->string('format', 50)->nullable()->after('logo')->comment('League, Knockout, GroupStage');
            $table->unsignedInteger('max_teams')->nullable()->after('format');
            $table->decimal('registration_fee', 15, 2)->nullable()->after('max_teams');
            $table->string('prize_pool')->nullable()->after('registration_fee');
            $table->date('registration_deadline')->nullable()->after('prize_pool');
            $table->string('pitch_type', 50)->nullable()->after('registration_deadline');
            $table->boolean('requires_approval')->default(true)->after('pitch_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'format',
                'max_teams',
                'registration_fee',
                'prize_pool',
                'registration_deadline',
                'pitch_type',
                'requires_approval'
            ]);
        });
    }
};
